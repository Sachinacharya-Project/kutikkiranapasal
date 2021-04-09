// const all_options = document.querySelectorAll('.options');
// console.log(all_options);
// all_options.forEach(options => {
//     options.addEventListener('click', ()=>{
//         if(!options.classList.contains('active')){
//             const classname = options.getAttribute(['data-class'])
//             document.querySelector(`.${classname}`).classList.add('open')
//             all_options.forEach(opt=>{
//                 opt.classList.remove('sectionactive')
//             })
//             options.classList.add('sectionactive')
//         }
//     })
// })

const closing_windows = (id)=>{
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

    const itemname = document.createElement('input')
    itemname.type = 'text'
    itemname.name = 'itemname'
    itemname.classList.add('itemname')
    itemname.setAttribute('required', 'required')
    itemname.setAttribute('placeholder', 'Product Name')
    itemname.setAttribute('autocomplete', 'off')
    itemdiv.appendChild(itemname)

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

const calculate = (amount, rate)=>{
    return amount * rate
}