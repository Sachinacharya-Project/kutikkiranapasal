const menu_this = (arg)=>{
    const optmenu = document.querySelectorAll('.optmenu')
    const mainer_heading = document.querySelector('.mainer_heading')
    const text = arg.textContent
    mainer_heading.textContent = text
    optmenu.forEach(menu => {
        if(menu.textContent === text){
            if(!menu.classList.contains('sectionactive')){
                menu.classList.add('sectionactive')
            }
            const of_div = document.querySelector(`.${menu.getAttribute(['data-class'])}`)
            if(!of_div.classList.contains('opendiv')){
                of_div.classList.add('opendiv')
            }
        }else{
            menu.classList.remove('sectionactive')
            const of_div = document.querySelector(`.${menu.getAttribute(['data-class'])}`)
            of_div.classList.remove('opendiv')
        }
    })
}
const gettingallproducts = ()=>{
    products = [['Ghee', '12 ltr'], ['Water', '12 ltr']]
    return products
}
const closing_windows = (id)=>{
    console.log(id);
    const getwindow = document.getElementById(id)
    getwindow.classList.remove('activate')
}

const opening_windows = (id)=>{
    const special_context = ['creating_orders', 'viewing-orders']
    const index = special_context.indexOf(id)
    if (index === -1){
        const getwindow = document.getElementById(id)
        getwindow.classList.add('activate')
    }else{
        document.getElementById('editing_content').classList.remove('activate')
        special_context.forEach(inner =>{
            if(inner != special_context[index]){
                const getwindow = document.getElementById(inner)
                getwindow.classList.remove('activate')
            }else{
                const getwindow = document.getElementById(inner)
                getwindow.classList.add('activate')
            }
        })
    }
}

count = 1
const add_product = ()=>{
    count += 1
    const container = document.getElementById('items-details') // items
    const itemdiv = document.createElement('div')
    itemdiv.classList.add('items')
    itemdiv.id = count;
    container.appendChild(itemdiv)

    const itemname = document.createElement('select')
    itemname.name = 'itemname'
    itemname.classList.add('itemname')
    itemdiv.appendChild(itemname)
    const prod = gettingallproducts()
    console.log(prod);
    let opts = `<option value="null">Choose Product</option>`
    prod.forEach(small_prod => {
        const name = small_prod[0].toLowerCase()
        opts += `<option value='${name}'>${small_prod[0]}(${small_prod[1]})</option>`
    })
    itemname.innerHTML = opts;
    const itemquantity = document.createElement('input')
    itemquantity.type = 'text'
    itemquantity.name = 'itemquantity'
    itemquantity.classList.add('itemquantity')
    itemquantity.setAttribute('required', 'required')
    itemquantity.setAttribute('placeholder', 'Quantity')
    itemquantity.setAttribute('autocomplete', 'off')
    itemquantity.setAttribute('data-parent', count)
    itemquantity.setAttribute('onkeyup', `calculateTotal(${count})`)
    itemdiv.appendChild(itemquantity)
    
    const peramount = document.createElement('input')
    peramount.type = 'text'
    peramount.name = 'peramount'
    peramount.classList.add('peramount')
    peramount.setAttribute('required', 'required')
    peramount.setAttribute('placeholder', 'Rate')
    peramount.setAttribute('autocomplete', 'off')
    peramount.setAttribute('data-parent', count)
    peramount.setAttribute('onkeyup', `calculateTotal('${count}')`)
    itemdiv.appendChild(peramount)
 
    const myspan = document.createElement('span')
    myspan.classList.add('total')
    myspan.textContent = 'Total: 0/-'
    myspan.setAttribute('data-total', '0')
    itemdiv.appendChild(myspan)
}

// Adding Addbutton Container
document.getElementById('add_prod').addEventListener('click', add_product)

const calculateTotal = (param)=>{
    const quantityItem = document.getElementById(count)
    const quantity = quantityItem.querySelector('.itemquantity').value
    const peramount = quantityItem.querySelector('.peramount').value
    const board = quantityItem.querySelector('.total')
    const total = calculate(quantity, peramount)
    board.textContent = `Total: ${total}/-`
    board.setAttribute('data-total', total)
}

const viewMineOrders = (refno)=>{
    $.post('/0/showproducts.php', {
        refno
    },(data, status)=>{
        const alldatas = data.split(',')
        const customers_name = alldatas[0].split(';')[0]
        const date = alldatas[0].split(';')[6]
        const address = alldatas[0].split(';')[7]
        const showing_output_of_orders = document.getElementById('showing_output_of_orders')
        // Adding main details
        const details_div = showing_output_of_orders.querySelector('.details')
        tag = `
        <p>Name: <span>${customers_name}</span></p>
        <p>Ref no.: <span>${refno}</span></p>
        <p>Order Date: <span>${date}</span></p>
        <p>Address: <span>${address}</span></p>
        `
        details_div.innerHTML = tag
        const itemsdetails = showing_output_of_orders.querySelector('.itemlist')
        let total = 0
        let tager = ''
        for(let i=0; i < alldatas.length-1; i++){
            const selection = alldatas[i].split(';')
            tager += `
            <p>${selection[1] == null ? 0 : selection[1]} &times; ${selection[2] == null ? 0 : selection[2]} @ ${selection[3] ==null ? 0 : selection[3]}</p>
            `
            total += calculate(selection[2], selection[3])
            
        }
        tager += `
            <p class="complete">Total: ${total}/-</p>                
        `
        itemsdetails.innerHTML = tager
        showing_output_of_orders.querySelector('.buttons .editit').setAttribute('onclick', `editthusout('${refno}')`)
        showing_output_of_orders.classList.add('activate')
    })
}

const printout = ()=>{
    const maindiv = document.querySelector('.showing_output_of_orders .contents').innerHTML
    const backup = document.body.innerHTML
    document.body.innerHTML = maindiv
    window.print()
    document.body.innerHTML = backup
}

const editthusout = (refno)=>{
    // Collecting Output Space
    const editing_content = document.getElementById('editing_content')
    const innerDiv = editing_content.querySelector('.innerDiv')
    $.post('/0/showproducts.php', {
        update: 'update',
        bill: refno
    }, (data, status)=>{
        closing_windows('showing_output_of_orders')
        closing_windows('viewing-orders')
        innerDiv.innerHTML = data
        editing_content.classList.add('activate')
    })
}

const updateContents = ()=>{
    const mainContainer = document.querySelector('.editing_content')
    const customersname = mainContainer.querySelector('.details .nameCos .cosname').value
    const address = mainContainer.querySelector('.details .addrCos .cosaddr').value
    const allitems = mainContainer.querySelectorAll('.items-editing .items')
    let total = 0
    allitems.forEach(minor =>{
        const productname = minor.querySelector('.item').value
        const quantity = minor.querySelector('.quantity').value
        const rate = minor.querySelector('.rate').value
        const states = minor.querySelector('.status').value
        if (total === 0){
            allitems.forEach(mini => {
                const quan = mini.querySelector('.quantity').value 
                const rat = mini.querySelector('.rate').value
                total += calculate(quan, rat)
            })
        }
        $.post('/0/place_order.php', {
            type: 'type',
            total,
            customersname,
            address,
            id: minor.getAttribute(['data-id']),
            quantity,
            rate,
            productname,
            states
        }, (data, status)=>{
            minor.remove()
            //details items-editing

        })
    })

    mainContainer.querySelector('.details').remove()
    mainContainer.querySelector('.items-editing').remove()
    mainContainer.classList.remove('activate')
    swal("Updated Products", "The Orders has been updated Successfully!", "success");
}

loading_counter = 2
const create_new_tab = ()=>{
    const main_container = document.querySelector('.of_inventory .allcontainers .purchasing-items')
    const inner_button = document.querySelector('.of_inventory .allcontainers .button button')
    
    standard_div = document.createElement('div')
    standard_div.classList.add('items')
    standard_div.setAttribute('data-index', loading_counter)
    main_container.appendChild(standard_div)

    const input = document.createElement('input')
    input.classList.add('item-name')
    input.setAttribute('required', 'required')
    input.setAttribute('autocomplete', 'off')
    input.placeholder = `${loading_counter} Product Name`
    standard_div.appendChild(input)

    const unit = document.createElement('select')
    unit.classList.add('unit')
    standard_div.appendChild(unit)
    unit.innerHTML = `
    <option value="regular">General (eg. 1 Biscuit)</option>
    <option value="kilo">Kilo</option>
    <option value="litre">Litre</option>
    <option value="dozan">Dozan</option>
    `
    const quantity = document.createElement('input')
    quantity.classList.add('quantity')
    quantity.placeholder = 'Quantity'
    quantity.setAttribute('required', 'required')
    quantity.setAttribute('onkeyup', `update_total(${loading_counter})`)
    quantity.autocomplete = 'off'
    standard_div.appendChild(quantity)
    
    const rate = document.createElement('input')
    rate.classList.add('rate')
    rate.placeholder = 'Rate'
    rate.setAttribute('required', 'required')
    rate.autocomplete = 'off'
    rate.setAttribute('onkeyup', `update_total("${loading_counter}")`)
    standard_div.appendChild(rate)

    const main_total = document.createElement('p')
    main_total.classList.add('total')
    main_total.textContent = 'Total: 0/-'
    standard_div.appendChild(main_total)

    inner_button.textContent = 'New Product Added'
    inner_button.classList.add('act')
    setTimeout(()=>{
        inner_button.textContent = `Add Product (${loading_counter-1} + 1)`
        inner_button.classList.remove('act')
    }, 200)
    loading_counter++;
}

const calculate = (amount, rate)=>{
    return amount * rate
}

const closer_look = (id)=>{
    const ids = ['viewing_purchases', 'making_purchase', 'adding_products', 'showing_products']
    ids.forEach(simps => {
        if(simps === id){
            document.getElementById(simps).classList.add('activate')
        }else{
            document.getElementById(simps).classList.remove('activate')
        }
    })
    if(id === 'showing_products'){
        showprod();
    }
}

const data_view = ()=>{
    const typed = document.getElementById('filtering_this_item').value
    const output_area = document.querySelector(".allcontainers .viewing_purchases .containers")
    $.post('/0/inventory.php', {
        typed,
    }, (data, status)=>{
        output_area.innerHTML = data;
        console.log(status);
    })
}

const show = (ref_no)=>{
    const showing_container = document.getElementById('data_lockdown')
    $.post('/0/inventory.php', {
        show: ref_no
    }, (data, status)=>{
        showing_container.innerHTML = data;
        closing_windows('viewing_purchases');
        showing_container.classList.add('activate')
    })
}


const make_purchase_please = ()=>{
    const main_container = document.getElementById('making_purchase')
    /* Collecting Static Datas */
    const shopname = main_container.querySelector('.shopkeeperdetails p .shopname').value
    const shopaddress = main_container.querySelector('.shopkeeperdetails p .shopaddr').value
    const payment_type = main_container.querySelector('.shopkeeperdetails p .payment_type').value
    const ispaid = main_container.querySelector('.shopkeeperdetails p .ispaid').value
    const isdelivered = main_container.querySelector('.shopkeeperdetails p .isDelivered').value
    let account;
    if (payment_type === 'Bank Transfer'){
        account = prompt('Account Number (Receiver)')
    }else if(payment_type == 'Others'){
        account = prompt('Please Specify Payment Method')
    }
    else{
        account = ''
    }
    const inner_items = main_container.querySelectorAll('.purchasing-items .items')
    let total = 0
    const datas = []
    const date = new Date()
    // Calender
    const years = date.getUTCFullYear()
    let months = date.getUTCMonth() + 1
    months = months < 10 ? '0'+months : months
    let day = date.getUTCDate()
    day = day < 10 ? '0'+day : day
    // Time
    const hours = date.getHours()
    let minutes = date.getMinutes()
    minutes = minutes<10 ? '0'+minutes : minutes
    const finalDate = years+'/'+months+'/'+day+' '+hours+':'+minutes;
    inner_items.forEach(minor_items => {
        const side_data = []
        const itemname = minor_items.querySelector(".item-name").value
        const unit = minor_items.querySelector(".unit").value
        const rate = minor_items.querySelector('.rate').value
        const quantity = minor_items.querySelector('.quantity').value
        const tots = calculate(quantity, rate)
        total += tots
        side_data.push(itemname, unit, rate, quantity, tots)
        datas.push(side_data)
    })
    $.post('/0/inventory.php', {
        shopname,
        shopaddress,
        ispaid,
        isdelivered,
        payment_type,
        account,
        finalDate,
        total,
        constdata: datas
    }, (data, status)=>{
        console.log(data);
        inner_items.forEach(m => {
            if(m.getAttribute(['data-index']) != '1'){
                m.remove()
            }else{
                m.querySelector(".item-name").value = null;
                m.querySelector(".quantity").value = null;
                m.querySelector(".rate").value = null;
                m.querySelector(".total").textContent = 'Total: 0/-';
            }
        })
        const showing_container = document.getElementById('data_lockdown')
        main_container.classList.remove('activate')
        showing_container.innerHTML = data;
        showing_container.classList.add('activate');
        swal(`You Have Total Expenditure of Rs ${total}/-`)
    })
}

const update_total = (index)=>{
    const main_container = document.querySelectorAll('.of_inventory .allcontainers .purchasing-items .items')
    const quantity = main_container[index-1].querySelector('.quantity').value
    const rate = main_container[index-1].querySelector('.rate').value
    const total_div = main_container[index-1].querySelector('.total')
    const total = calculate(quantity, rate)
    total_div.textContent = `Total: ${total}/-`
}

let prodcout = 2
const create_add_product = ()=>{
    const main_container = document.querySelector('.adding_products .innerHtml .details')
    const templete = `
    <div class="items">
        <p>
            <input type="text" class="prodname" placeholder="${prodcout} Product Name" required="required" autocomplete="none">
        </p>
        <p>
            <input type="text" class="instock" placeholder="Quantity InStock" required="required" autocomplete="none">
        </p>
    </div>
    `;
    main_container.insertAdjacentHTML('afterbegin', templete)
    prodcout++;
}

const save_product = (args)=>{
    if(args == 'saving'){
        const myarray = [];
        const datas_stream = document.querySelectorAll('.adding_products .details .items')
        datas_stream.forEach(item => {
            const prdname = item.querySelector('.prodname').value
            const instock = item.querySelector('.instock').value
            if(prdname != null || instock != null){
                const newA = [prdname, instock]
                myarray.push(newA)
            }
        })
        $.post('/0/inventory.php', {
            type: 'saving',
            myarray
        }, (data, status)=>{
            swal(data)
            datas_stream.forEach(i => {
                if(!i.classList.contains('items_one')){
                    i.remove()
                }else{
                    i.querySelector('.prodname').value = ''
                    i.querySelector('.instock').value = ''
                }
            })
        })
    }
}

const of_inventory_select = document.querySelector(".of_inventory .controllers select")
of_inventory_select.addEventListener('change', ()=>{
    closer_look(of_inventory_select.value)
})
const showprod = ()=>{
    $.post('/0/inventory.php', {
        showprod: 'show'
    }, (data, status)=>{
        const showing_products = document.querySelector('.showing_products .details')
        console.log(data);
        showing_products.innerHTML = data;
    })
}