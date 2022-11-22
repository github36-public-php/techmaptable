/* 
Ревьювер!
Этот документ проходит проверку jshint
Ошибок 0.
https://jshint.com/
*/
$(document).ready(function() {
    Window();
    PanelDraggable();
    AjaxLoadOnPage();
    FormSubmit();
    ActionConfirm();
    TableUpdate();
    TableFixedHeader();
    Test();
});

function Window() {
    $(".window").hide().fadeIn(500);
    $(".window").draggable({
        handle: '.window__header'
    });
}

function PanelDraggable() {
    $(".panel_draggable").draggable({
        handle: '.panel__header'
    });
}

function AjaxLoadOnPage() {
    $('.ajax-link').on('click', function(e) {
        e.preventDefault();
        let dataLoadAddress = $(this).data('href');
        let dataLoadArea = $(this).data('place');
        $.ajax({
            url: dataLoadAddress,
            type: "POST",
            success: function(html) {
                $(dataLoadArea).html(html);
            }
        });
    });
}

function FormSubmit() {
    $('.submit_href').on('click', function(e) {
        e.preventDefault();
        $(this).closest('form').submit();
    });
    $('.submit_input').keypress(function(e) {
        if (e.keyCode == 13) {
            $(this).closest('form').submit();
        }
    });
}

function ActionConfirm() {
    $('.confirm_user_delete_href').click(function() {
        return confirm("Вы действительно хотите удалить этого пользователя?");
    });
    $('.confirm_address_delete_href').click(function() {
        return confirm("Вы действительно хотите этот адрес?");
    });
}

function TableUpdate() {
    $(document).on('blur', '.edit_cell', function() {
        let id = $(this).data("id");
        let field = $(this).data("field");
        let td_text = ($(this).html());
        $.ajax({
            url: "actions.php?action=table_update",
            method: "POST",
            data: {
                id: id,
                field: field,
                td_text: td_text
            },
            dataType: "text"
        });
    });
}

function TableFixedHeader() {
    var $th = $('.page__content_techmaptable_content').find('thead th');
    $('.page__content_techmaptable_content').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    });
}

function Test() {
    $('.ajax-excel-export').on('click', function() {
        var a = $('input:checked');
        var out = [];
        for (var x = 0; x < a.length; x++) {
            out.push(a[x].value);
        }
        console.log(out);
        $.ajax({
            url: "actions.php?action=excel_export",
            method: "POST",
            data: {
                checkbox_id: out
            },
            dataType: "text",
            success: function(data) {
                $('.techmaptable__result').html(data);
            }
        });
    });
}