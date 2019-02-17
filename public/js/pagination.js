$(function () {

    let $stagesList = $('#stages-list .jobs');
    let $spinner = $('#stages-loading-spinner');

    $(document).on('click', '.pagination .page-link', function () {

        let $pagination = $('.pagination');
        let pageNumber = parseInt($(this).attr('data-page-number'));

        $.ajax({
            dataType: "json",
            url: '/stage/api/pagination/get',
            data: {
                page: pageNumber,
                paginationLimit: 3,
                itemsLimit: 3
            },
            success: function (jsonResponse) {
                let tmpPaginationHTML = '';
                let tmpExamsListHTML = '';

                // Crate items list

                jsonResponse.stages.forEach(function (stage) {
                    tmpExamsListHTML += 
                    '<div class="job-container">' +
                        '<div class="job-img-container">' +
                            '<a href="/egzaminy/'+ stage.id +'-'+ stage.friendly_url +'">' +
                                '<img class="job-img" src="uploads/stage/'+ stage.image_name +'" alt="Wybierz kategorie">' +
                            '</a>' +
                        '</div>' +
                        '<div class="job-info">' +
                            '<h3 class="info-title">' +
                            '<a href="/egzaminy/'+ stage.id +'-'+ stage.friendly_url +'">'+ stage.designation +'</a>' +
                        '</h3>' +
                            '<div class="info-text">'+ stage.stages_quantity +' kwalifikacje</div>' +
                            '<div class="info-text">'+ stage.qualification_quantity +' pytań w bazie</div>' +
                        '</div>' +
                        '<div style="clear:both"></div>' +
                    '</div>';
                });

                // Make pagination 
                if (jsonResponse.currentPage !== 1) {
                    tmpPaginationHTML += 
                        '<li class="page-item">' +
                            '<div class="page-link" rel="prev" data-page-number="'+ (jsonResponse.currentPage - 1) +'" aria-label="Previous">' +
                                '<span aria-hidden="true">&laquo;</span>' +
                                '<span class="sr-only">Previous</span>' +
                            '</div>' +
                        '</li>';
                } else {
                    tmpPaginationHTML += 
                        '<li class="page-item disabled">' +
                            '<div class="page-link">' +
                                '<span aria-hidden="true">&laquo;</span>' +
                                '<span class="sr-only">Previous</span>' +
                            '</div>' +
                        '</li>';
                }

                jsonResponse.range.forEach(function (element) {
                    let isActive = '';

                    if (jsonResponse.currentPage === element)
                        isActive = ' active';

                        tmpPaginationHTML += 
                        '<li class="page-item'+ isActive +'">' +
                            '<div class="page-link" data-page-number="'+ element +'">'+ element +'</div>' +
                        '</li>';
                });

                if (jsonResponse.allPages == jsonResponse.currentPage) {
                    tmpPaginationHTML += 
                        '<li class="page-item disabled">' +
                            '<div class="page-link">' +
                                '<span aria-hidden="true">&raquo;</span>' +
                                '<span class="sr-only">Next</span>' +
                            '</div>' +
                        '</li>';
                } else {
                    tmpPaginationHTML += 
                        '<li class="page-item">' +
                            '<div class="page-link" rel="next" data-page-number="'+ (jsonResponse.currentPage + 1) +'">' +
                                '<span aria-hidden="true">&raquo;</span>' +
                                '<span class="sr-only">Previous</span>' +
                            '</div>' +
                        '</li>';
                }

                $stagesList.html(tmpExamsListHTML);
                $pagination.html(tmpPaginationHTML);
            },
            beforeSend: function() {
                $spinner.show();
            },
            complete: function() {
                let navbarHeight = $('.navbar').height();
                
                $spinner.hide();
                $('html, body').animate({
                    scrollTop: $stagesList.offset().top + navbarHeight - 250
                });
            },
        });
    });
})