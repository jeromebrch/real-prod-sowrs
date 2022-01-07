$(window).on('load', function (){
    let popUpCalendar = $('.pop_up_calendar');
    let btnCloseCalendarPopUp = $('.close_pop_up');
    if(popUpCalendar){
    setTimeout(function (){
        popUpCalendar.show(500);
    }, 2000);

    btnCloseCalendarPopUp.on('click', function (){
        popUpCalendar.hide(500);
            $.ajax({
            method: 'POST',
            url: baseURL + "/closePopUp"
            })
            .done(function (){
                // alert('Le pop up ne s\'affichera plus pour cette session')
            })
            .fail(function(data){
                // console.log(data)
            })
        });
    }
})