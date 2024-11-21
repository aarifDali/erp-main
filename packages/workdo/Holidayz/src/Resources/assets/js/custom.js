$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var site_url = $('meta[name="base-url"]').attr('content');


$(document).ready(function() {
    var table = $('#dataTable-export').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'print']
    } );

    table.buttons().container()
        .appendTo( '#dataTable-export_wrapper .col-md-6:eq(0)' );


} );

$(document).ready(function () {
    $('.dataTable').DataTable();
});



$(document).on('input', '.autogrow', function () {
    $(this).height("auto").height($(this)[0].scrollHeight - 18);
});


$(document).on('click', '.show_confirm', function (e) {
    var form = $(this).closest("form");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
});


$(document).on("click", '.bs-pass-para-pos', function () {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            document.getElementById($(this).data('confirm-yes')).submit();

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
        }
    })
});

$(document).on("click", '.bs-pass-para', function () {
    var form = $(this).closest("form");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            form.submit();

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
        }
    })
});

function comman_function() {
    if ($('[data-role="tagsinput"]').length > 0) {
        $('[data-role="tagsinput"]').each(function (index, element) {
            var obj_id = $(this).attr('id');
            var textRemove = new Choices(
                document.getElementById(obj_id), {
                delimiter: ',',
                editItems: true,
                removeItemButton: true,
            }
            );
        });
    }
}


PurposeStyle = function () {
    var e = getComputedStyle(document.body);
    return {
        colors: {
            gray: {100: "#f6f9fc", 200: "#e9ecef", 300: "#dee2e6", 400: "#ced4da", 500: "#adb5bd", 600: "#8898aa", 700: "#525f7f", 800: "#32325d", 900: "#212529"},
            theme: {
                primary: e.getPropertyValue("--primary") ? e.getPropertyValue("--primary").replace(" ", "") : "#6e00ff",
                info: e.getPropertyValue("--info") ? e.getPropertyValue("--info").replace(" ", "") : "#00B8D9",
                success: e.getPropertyValue("--success") ? e.getPropertyValue("--success").replace(" ", "") : "#36B37E",
                danger: e.getPropertyValue("--danger") ? e.getPropertyValue("--danger").replace(" ", "") : "#FF5630",
                warning: e.getPropertyValue("--warning") ? e.getPropertyValue("--warning").replace(" ", "") : "#FFAB00",
                dark: e.getPropertyValue("--dark") ? e.getPropertyValue("--dark").replace(" ", "") : "#212529"
            },
            transparent: "transparent"
        }, fonts: {base: "Nunito"}
    }
}

var PurposeStyle = PurposeStyle();

/********* Cart Popup ********/
$('.wish-header').on('click',function(e){
    e.preventDefault();
    setTimeout(function(){
    $('body').addClass('no-scroll wishOpen');
    $('.overlay').addClass('wish-overlay');
    }, 50);
});
$('body').on('click','.overlay.wish-overlay, .closewish', function(e){
    e.preventDefault();
    $('.overlay').removeClass('wish-overlay');
    $('body').removeClass('no-scroll wishOpen');
});


// below code use for multiple selectbox


$(document).ready(function () {
    select2();
});


function select2() {
    if ($(".select2").length > 0) {
        $($(".select2")).each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
}

$(document).on('change', '.hotel_domain_click#enable_storelink', function (e) {
    $('#hotelStoreLink').show();
    $('.hotelsundomain').hide();
    $('.hoteldomain').hide();
    $('#hoteldomainnote').hide();
    $( ".hotellink" ).parent().addClass('active');
    $( "#enable_domain" ).parent().removeClass('active');
    $( "#enable_subdomain" ).parent().removeClass('active');
});
$(document).on('change', '.hotel_domain_click#enable_domain', function (e) {
    $('.hoteldomain').show();
    $('#hotelStoreLink').hide();
    $('.hotelsundomain').hide();
    $('#hoteldomainnote').show();
    $( "#enable_domain" ).parent().addClass('active');
    $( ".hotellink" ).parent().removeClass('active');
    $( "#enable_subdomain" ).parent().removeClass('active');
});
$(document).on('change', '.hotel_domain_click#enable_subdomain', function (e) {
    $('.hotelsundomain').show();
    $('#hotelStoreLink').hide();
    $('.hoteldomain').hide();
    $('#hoteldomainnote').hide();
    $( "#enable_subdomain" ).parent().addClass('active');
    $( "#enable_domain" ).parent().removeClass('active');
    $( "#enable_domain" ).parent().removeClass('active');
});


$(document).on('click', '.fc-daygrid-event', function (e) {
    
    // if (!$(this).hasClass('project')) {
    e.preventDefault();
    var event = $(this);
    var title1 = $(this).find("fc-event-title").innerHtml;
    var title2 = $(this).data("bs-original-title");
    var title = (title1 != undefined) ? title1 : title2;
    if(title == undefined){
        title = 'Booking';
    }
    var size = ($(this).data('size') == '' || $(this).data('size') == undefined) ? 'lg' : $(this).data('size');
    var url = $(this).attr('href');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            common_bind();
        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
    // }
});

function common_bind() {
    select2();
}


// icon picker
if ($('[role="iconpicker"]').length > 0) {
    $('[role="iconpicker"]').iconpicker();
}
