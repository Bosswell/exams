$(function () {
    let $searchInput = $('#search');
    let $searchGroup = $('#search-group');
    let $searchContainer = $searchGroup.find('#search-container');
    let $notFoundMessage = $('#not-found-message');
    let $notEnoughLettersMessage = $('#not-enough-letters-message');
    let timer = null;
    let query = '';
    let $ellipsis = $('.jobs #ellipsis-container .lds-ellipsis');

    $searchInput.on('keyup', function () {
        clearTimeout(timer);
            $ellipsis.show();

        timer = setTimeout( () => {
            query = $(this).val();

            if (query.length === 0) {
                $searchGroup.hide();
                $ellipsis.hide();

                setTimeout( () => {
                    $notEnoughLettersMessage.hide();
                    $notFoundMessage.hide();
                }, 3000);

            } else if (query.length >= 3) {
                $notEnoughLettersMessage.hide();

                $.ajax({
                    dataType: "json",
                    url: '/stage/api/find',
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
                                            '<a href="/egzaminy/'+ element.id +'-'+ element.friendly_url +'">'+ element.designation + '</a>' +
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
                            $notFoundMessage.hide();
                            $notEnoughLettersMessage.hide();

                            $searchContainer.html(tmpHTML);
                            $searchGroup.fadeIn();
                        }

                        $ellipsis.hide();
                    }
                })
            } else {
                $ellipsis.hide();
                $searchGroup.hide();
                $notFoundMessage.hide();
                $notEnoughLettersMessage.show();
            }
        }, 700);
    });
})