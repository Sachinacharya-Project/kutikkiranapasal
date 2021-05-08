/**
 * Live Search System
 */

const parent_container = document.querySelector('.of_customers .inner_container')
const search_bar = parent_container.querySelector(".search_area input")
search_bar.onkeyup = ()=>{
    /**
     * Collecting values thats been input by users and posting to the searver and get back results
     */
    const input_value = search_bar.value
    if(input_value){
        $.post('../../0/customers.php', {
            input_value
        }, (data, status)=>{
            if(data){
                const customers_area_will_be_here = parent_container.querySelector(".customers_area_will_be_here")
                customers_area_will_be_here.innerHTML = data;
            }
        })
    }
}
/**
 * Adding event on the select 
 * To get the filter results and show them all to em
 */

const select_options = parent_container.querySelector('.options select')
select_options.addEventListener('change', ()=>{
    $.post("../../0/customers.php", {
        request: select_options.value
    }, (data, status)=>{
        if(data){
            const customers_area_will_be_here = parent_container.querySelector(".customers_area_will_be_here")
            customers_area_will_be_here.innerHTML = data;
        }
    })
})

const close_th = ()=>{
    document.querySelector(".inner_container").classList.add("activate")
    document.querySelector(".showing_out").classList.remove("activate")
}

const customer_preview = (item)=>{
    let index = item.getAttribute(["data-index"])
    $.post("../../0/customers.php", {
        get_s: index
    }, (data, status)=>{
        const output = document.querySelector(".showing_out")
        if(data){
            output.innerHTML = data
            output.classList.add("activate")
            document.querySelector(".inner_container").classList.remove("activate")
        }
    })
}