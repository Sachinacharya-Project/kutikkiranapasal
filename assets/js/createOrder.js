$(document).ready(()=>{
    $("#placeorder").click(()=>{
        let total_received_amount = 0
        const customer_name_bar = document.getElementById('customersName')
        const customersaddress_bar = document.querySelector(".customersaddress");
        const amount_received_bar = document.querySelector(".asking_amount");

        let customer = customer_name_bar.value
        const customersnames = customer.split(' ')
        customer = ''
        customersnames.forEach(partedname =>{
            customer += partedname.charAt(0).toUpperCase() + partedname.slice(1)+' '
        })

        customeraddress = customersaddress_bar.value

        amount_received = amount_received_bar.value
        let date = new Date();
        const year = date.getFullYear();
        let month = date.getMonth()+1;
        month = month<10 ? "0"+month : month;
        let today = date.getUTCDate();
        today = today < 10 ? "0"+today : today 
        let hours = date.getHours();
        hours = hours < 10 ? "0"+hours : hours;
        let minutes = date.getMinutes();
        minutes = minutes < 10 ? "0"+minutes : minutes
        date = `${year}-${month}-${today} ${hours}:${minutes}`
        const data_list = [];
        // Getting all the products
        const items_list = document.querySelectorAll('.creating_orders .items-details .items')
        items_list.forEach(individual_items => {
            const product_name = individual_items.querySelector(".itemname").value
            const product_quantity = individual_items.querySelector(".itemquantity").value
            const product_rate = individual_items.querySelector('.peramount').value
            const individual_total = individual_items.querySelector(".total").getAttribute(['data-total'])
            total_received_amount += parseInt(individual_total)
            const array = [product_name, product_quantity, product_rate, individual_total]
            data_list.push(array);
            if(individual_items.id != "1" || individual_items.id != 1){
                individual_items.remove()
            }
        })
        $.post("../../0/place_order.php", {
            customer,
            customeraddress,
            amount_received,
            total_received_amount,
            date,
            data_list
        }, (data, status)=>{
            closing_windows('creating_orders')
            if(data){
                console.log(data);
                const showing_output = document.getElementById("showing_output");
                // Basics
                const basic_contents = showing_output.querySelector(".contents .basics")
                showing_output.querySelector(".res-refno").textContent = data[0];
                showing_output.querySelector(".res-customername").textContent = customer;
                showing_output.querySelector(".res-order").textContent = data[2];
                showing_output.querySelector(".res-checkdate").textContent = data[2];
                showing_output.querySelector(".res-total").textContent = data[4];
                showing_output.querySelector(".res-actual-cost").textContent = data[3];
                let datastring = `${data[1]} (Credited)`;
                if(data[1] < 0){
                    datastring = `${data[1]} (Debited)`;
                }
                showing_output.querySelector(".res-debit-credit").textContent = datastring;
                const that_table = showing_output.querySelector('.contents .allitems table')
                let html = '';
                data_list.forEach(prd => {
                    html = `
                        <tr>
                            <td>${prd[0]}</td>
                            <td>${prd[1]}</td>
                            <td>${prd[2]}</td>
                            <td>${prd[3]}</td>
                            <td class='pending'>PENDING</td>
                        </tr>
                    `
                })
                that_table.insertAdjacentHTML("beforeend", html);
                opening_windows("showing_output")
            }
        })
    })
    const view_order = document.getElementById('view-orders')
    // Divinsion
    const viewing_orders = document.querySelector('.viewing-orders .details')
    // Events
    view_order.addEventListener('click', ()=>{
        $.post('/0/showproducts.php', 
        {
            get:'get'
        }, (data, status)=>{
            viewing_orders.innerHTML = data
        })
    })
    const live_search_bar = document.getElementById('search-bar')
    live_search_bar.onkeyup = ()=>{
        const search_data = live_search_bar.value
        if(search_data != ''){
            $.post('/0/showproducts.php', {
                get:search_data
            }, (data, status)=>{
                viewing_orders.innerHTML = data
            })
        }else{
            $.post('/0/showproducts.php', {
                get:'get'
            }, (data, status)=>{
                viewing_orders.innerHTML = data
            })
        }
    }
    const customersNameLive = document.querySelector('.customersNameLive')
    const all_the_customer = document.querySelector('.all_the_customer')
    customersNameLive.onclick = ()=>{
        all_the_customer.classList.add("active")
    }
    customersNameLive.onkeyup = ()=>{
        $.post("../../0/place_order.php", {
            get_back_span: customersNameLive.value
        },(data, status)=> {
            if(data){
                all_the_customer.innerHTML = data
            }
        })
    }
})

const customersNameLive = document.querySelector('.customersNameLive')
const all_the_customer = document.querySelector('.all_the_customer')

const update_customer_name = (data)=>{
    customersNameLive.value = data;
    all_the_customer.classList.remove('active')
}

document.addEventListener('click', (e)=>{
    if(e.target.classList.contains('target_free') || e.target.classList.contains("customersNameLive")){
    }else{
        if(all_the_customer.classList.contains('active')){
            all_the_customer.classList.remove('active')
        }
    }
})