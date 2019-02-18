$(function () {
    let navbarItem = $('.navbar .nav-item');
    let examsPosition = $('#egzaminy').offset().top - 100;
    let deadlinePosition = $('#terminy').offset().top - 100;
    let contactPosition = $('#contact').offset().top - 100;

    checkPosition();

    $($(window).scroll(function () {
        checkPosition();
    }))

    function checkPosition() {
        let topPosition = $(window).scrollTop();

        if (contactPosition <= topPosition) {
        navbarItem.eq(2).addClass('active');
    
        navbarItem.eq(0).removeClass('active');
        navbarItem.eq(1).removeClass('active');
    
        } else if (deadlinePosition <= topPosition) {
        navbarItem.eq(1).addClass('active');
    
        navbarItem.eq(0).removeClass('active');
        navbarItem.eq(2).removeClass('active');
    
        } else if (examsPosition <= topPosition) {
        navbarItem.eq(0).addClass('active');
    
        navbarItem.eq(2).removeClass('active');
        navbarItem.eq(1).removeClass('active');
        } else {
        navbarItem.eq(0).removeClass('active');
        navbarItem.eq(1).removeClass('active');
        navbarItem.eq(2).removeClass('active');
        }
    }
})