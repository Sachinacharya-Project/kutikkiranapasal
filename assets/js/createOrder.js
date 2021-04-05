$(document).ready(()=>{
    $('#placeorder').click(()=>{
        let customer = document.getElementById('customersName').value
        const customersnames = customer.split(' ')
        customer = ''
        customersnames.forEach(partedname =>{
            customer += partedname.charAt(0).toUpperCase() + partedname.slice(1)+' '
        })
        const address = document.getElementById('customersaddress').value
        const alldivinsde = document.querySelectorAll('.items-details .items')
        let constdata = []
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
        total = 0
        alldivinsde.forEach(single => {
            const minorArray = []
            const productname = single.querySelector('.itemname').value
            const quantity = single.querySelector('.itemquantity').value
            const rate = single.querySelector('.peramount').value
            const itemtotal = single.querySelector('.total')
            total += parseInt(itemtotal.getAttribute(['data-total']))
            minorArray.push(productname)
            minorArray.push(quantity)
            minorArray.push(rate)
            minorArray.push(`${parseInt(itemtotal.getAttribute(['data-total']))}/-`)
            constdata.push(minorArray)
        })
    
        $.post('/0/place_order.php',{
            customername: customer,
            address,
            finalDate,
            data: constdata,
            total
        },
        (data, status)=>{
            console.log(status);
            const array = data.split(',')
            const receit = document.getElementById('showing_output')
            document.querySelector('.res-refno').textContent = '#'+array[0];
            document.querySelector('.res-customername').textContent = customer;
            document.querySelector('.res-order').textContent = array[1];
            document.querySelector('.res-checkdate').textContent = array[1];
            document.querySelector('.res-total').textContent = `NRS ${array[2]}/- (All. Tax Included)`;

            const allitems = receit.querySelector('.contents .allitems table')
            const tr = document.createElement('tr')
            allitems.appendChild(tr)
            let titles = ['Item', 'Quantity', 'Cost per item', 'Total Cost', 'Status']
            titles.forEach(til=>{
                td = document.createElement('td')
                td.textContent = til
                tr.appendChild(td)
            })

            constdata.forEach(subarray =>{
                const trd = document.createElement('tr')
                allitems.appendChild(trd)
                subarray.forEach(item => {
                    tdr = document.createElement('td')
                    tdr.textContent = item
                    trd.appendChild(tdr)
                })
                tdrl = document.createElement('td')
                tdrl.textContent = 'PENDING'
                tdrl.classList.add('pending')
                trd.appendChild(tdrl)
            })

            receit.classList.add('activate')
            const creating_orders = document.getElementById('creating_orders')
            creating_orders.classList.remove('activate')
            const allitemsithincreating = creating_orders.querySelectorAll(".items")
            allitemsithincreating.forEach(mine =>{
                if (mine.id != '1'){
                    mine.remove()
                }else{
                    const inputs = mine.querySelectorAll('input')
                    inputs.forEach(inpu => {
                        inpu.value = ''
                    })
                    mine.querySelector('.total').textContent = 'Total: 0/-'
                }
            })
            creating_orders.querySelectorAll('.customers-name input').forEach(inp=>{
                inp.value = ''
            })
        })
    })
    //Buttons
    const view_order = document.getElementById('view-orders')
    // Divinsion
    const viewing_orders = document.getElementById('viewing-orders')
    // Events
    view_order.addEventListener('click', ()=>{
        $.post('/0/showproducts.php', 
        {
            get:'get'
        }, (data, status)=>{
            const alldatasseparated = data.split(',')
            for (let i = 0;i<alldatasseparated.length - 1;i++){
                const maindata = alldatasseparated[i].split(';')
                viewing_orders.innerHTML += `
                <li onclick="viewMineOrders('${maindata[0]}')">
                <p>Name: ${maindata[1]}</p>
                <p>Order On: ${maindata[2]}</p>
                <p>Total: RS ${maindata[3]}/-</p>
                </li>
                `
            }
        })
    })
    
})