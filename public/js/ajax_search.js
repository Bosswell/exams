$(function () {
    let $searchInput = $('#search');
    let $searchGroup = $('#search-group');
    let $searchContainer = $searchGroup.find('#search-container');
    let $notFoundMessage = $('#not-found-message');
    let $notEnoughLettersMessage = $('#not-enough-letters-message');
    let timer = null;
    let query = '';
    let $spinner = $('#search-row .lds-spinner');
    let isMobile = null;

    detectMobile();
    $(window).resize(detectMobile);

    $searchInput.on('keyup', function () {
        clearTimeout(timer);
        if (!isMobile)
            $spinner.show();

        timer = setTimeout( () => {
            query = $(this).val();

            if (query.length === 0) {
                $searchGroup.hide();
                $spinner.hide();

                setTimeout( () => {
                    $notEnoughLettersMessage.hide();
                    $notFoundMessage.hide();
                }, 3000);

            } else if (query.length >= 3) {
                $notEnoughLettersMessage.hide();

                $.ajax({
                    dataType: "json",
                    url: '/stage/find',
                    data: {
                        query: query
                    },
                    success: function (jsonResponse) {
                        let tmpHTML = '';
                        
                        jsonResponse.forEach(element => {
                            tmpHTML += 
                                '<div class="job-container">' +
                                    '<div class="job-img-container">' +
                                        '<a href="/egzaminy/'+ element.friendly_url +'-'+ element.id +'">' +
                                            '<img class="job-img" src="/uploads/stage/'+ element.image_name +'" alt="Wybierz kategorie">' +
                                        '</a>' +
                                    '</div>' +
                                    '<div class="job-info">' +
                                        '<h3 class="info-title">' +
                                            '<a href="/egzaminy/'+ element.friendly_url +'-'+ element.id +'">'+ element.designation + '</a>' +
                                        '</h3>' +
                                        '<div class="info-text">'+ element.stages_quantity +' kwalifikacje</div>' +
                                        '<div class="info-text">'+ element.qualification_quantity +' pytań w bazie</div>' +
                                    '</div>' +
                                    '<div style="clear:both"></div>' +
                                '</div>';
                        });
                        
                        if (jsonResponse.length === 0) {
                            $notFoundMessage.show();
                            $searchGroup.hide();
                        } else {
                            $searchContainer.html(tmpHTML);
                            $searchGroup.fadeIn();
                        }

                        $spinner.hide();
                    }
                })
            } else {
                $spinner.hide();
                $notFoundMessage.hide();
                $notEnoughLettersMessage.show();
            }
        }, 700);
    });

    function detectMobile() {
        if ($(window).width() <= 768) {
            isMobile = true;
        } else {
            isMobile = false;
        }
    }

})