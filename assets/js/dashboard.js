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
    const getwindow = document.getElementById(id)
    getwindow.classList.add('activate')
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
    const total = Math.ceil(quantity * peramount)
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
        const address = alldatas[0].split(';')[7] || 'Bharatpur-2, Chitwan'
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
            total += selection[2] * selection[3]
            
        }
        tager += `
            <p class="complete">Total: ${total}/-</p>                
        `
        itemsdetails.innerHTML = tager
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