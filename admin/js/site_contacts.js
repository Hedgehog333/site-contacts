jQuery( document ).ready( function( $ ) {
	$('#add_contact').submit(function(e){
        e.preventDefault();

        $('#add_contact input').each(function(){
            $(this).removeClass('error');
        });
        $('#add_contact span.error').hide();

        var Code = $('#add_contact input[name="code"]');
        var Title = $('#add_contact input[name="title"]');
        var Value = $('#add_contact input[name="value"]');
        if(!validate(Code, Title, Value))
            return;

		$.post("/wp-admin/admin-ajax.php",
			{
				'action': 'hgh_insert_contact',
				code: Code.val(),
				title: Title.val(),
				value: Value.val()
			}
		, "json")
		.done(function(data)
        {
            var obj = JSON.parse(data);

            if(obj.result)
            {
                $('#add_contact input[type="text"]').val('');
                createTR(obj.data);
            }
            else if (obj.result == false)
            {
                errorFieldMessage(
                    $('#add_contact input[name="code"]'), 
                    'The value must be unique.'
                );
            }
		})
		.fail(function(xhr, status, error) {
            alert( 'Error ajax.\nOpen the Developer Console to view the error messages.');
			console.log( error );
		});
    });

    $(document).on('click', 'button.edit', function(e){
        e.preventDefault();
        console.log('edit');
        var row = $(this).parent().parent();
        var id = row.data('contactId');
        editCell(id, 0);
        editCell(id, 1);
        editCell(id, 2);

        var btn = row.children().eq(4).children("button");
        btn.removeClass();
        btn.addClass('cancel');
        btn.text('cancel');

        btn = $(this);
        btn.removeClass();
        btn.addClass('save');
        btn.text('save');
        return false;
    });

    $(document).on('click', 'button.delete', function(e){
        e.preventDefault();
        var id = $(this).parent().parent();
        $.post("/wp-admin/admin-ajax.php",
        {
            'action':'hgh_delete_contact',
            id: id.data('contactId')
        }
        , "json")
        .done(function(data) {
            id.remove();
        });
        return false;
    });

    $(document).on('click', 'button.save', function(e){
        e.preventDefault();
        var flag = true;
        var rowChild = $(this).parent().parent().children();
        var Code = rowChild.eq(0).children("input");
        var Title = rowChild.eq(1).children("input");
        var Value = rowChild.eq(2).children("input");
        var Id = $(this).parent().parent().data('contactId');
        
        var btn = $(this);

        if(!validate(Code, Title, Value))
            return;

        $.post("/wp-admin/admin-ajax.php",
        {
            'action':"hgh_update_contact",
            code: Code.val(),
            title: Title.val(),
            value: Value.val(),
            id: Id
        }
        , "json")
        .done(function(data)
        {
            var obj = JSON.parse(data);
            if(obj.result === false)
            {
                errorFieldMessage(
                    Code, 
                    'The value must be unique.'
                ); 
            }
            else if(obj.result)
            {
                $('.bordered [data-contact-id="'+Id+'"] input').each(function(){
                    var t = $(this).val();
                    $(this).parent().text(t)
                });

                var b = btn.parent().parent().children().eq(4).children("button");
                b.removeClass();
                b.addClass('delete');
                b.text('delete');

                btn.removeClass();
                btn.addClass('edit');
                btn.text('edit');
            }
        });

        return false;
    });

    $(document).on('click', 'button.cancel', function(e){
        e.preventDefault();

        var row = $(this).parent().parent();
        var id = row.data('contactId');
        cancelEditCell(id);

        var btn = $(this);
        btn.removeClass();
        btn.addClass('delete');
        btn.text('delete');

        btn = row.children().eq(3).children("button");
        btn.removeClass();
        btn.addClass('edit');
        btn.text('edit');

        return false;
    });

    function editCell(id, cellNum)
    {
        var cur = $('.bordered [data-contact-id="'+id+'"]').children().eq(cellNum);
        var dafaultText = cur.text();
        cur.empty();
        cur.append('<input type="text" ' + (cellNum==0?'pattern="[a-z0-9-_]{1,55}" title="Letters, numbers, hyphens, and underscores"':'' )+ ' required value="'+dafaultText+'" name="tv" /><span class="defval" style="display:none;">'+dafaultText+'</span><span class="error" style="display: none;"></span>');
    }

    function cancelEditCell(id)
    {
        var dafaultText = $('.bordered [data-contact-id="'+id+'"] span.defval').each(function(){
            var t = $(this).text();
            $(this).parent().text(t)
        });
    }
});

function createTR(data)
{
    console.log(data);
    var trElem = document.createElement("tr");
    trElem.setAttribute('data-contact-id', data.id);

    var tdElem1 = document.createElement("td");
    tdElem1.innerHTML = data.code;
    var tdElem2 = document.createElement("td");
    tdElem2.innerHTML = data.title;
    var tdElem3 = document.createElement("td");
    tdElem3.innerHTML = data.value;
    trElem.appendChild(tdElem1);
    trElem.appendChild(tdElem2);
    trElem.appendChild(tdElem3);

    var tdElem4 = document.createElement("td");
    var btnElem1 = document.createElement("button");
    btnElem1.className = 'edit';
    btnElem1.innerHTML = 'edit';
    tdElem4.appendChild(btnElem1);

    var tdElem5 = document.createElement("td");
    var btnElem2 = document.createElement("button");
    btnElem2.className = 'delete';
    btnElem2.innerHTML = 'delete';
    tdElem5.appendChild(btnElem2);

    trElem.appendChild(tdElem4);
    trElem.appendChild(tdElem5);

    document.getElementById('rows').appendChild(trElem);
}

function errorFieldMessage(elem, message)
{
    elem.addClass('error')
        .next('span.error')
        .html(message)
        .show();
    return false;
}

function validate(elemCode, elemTitle, elemValue)
{
    var pattern = /^[a-z0-9_-]{1,55}$/;
    var flag = true;
    if(elemCode.val() == '')
        flag = errorFieldMessage(
            elemCode,
            'The field must contain the value.'
        );
    else if(!pattern.test(elemCode.val()))
        flag = errorFieldMessage(
            elemCode, 
            'Letters, numbers, hyphens, and underscores.'
        );

    if(elemTitle.val() == '')
        flag = errorFieldMessage(
            elemTitle, 
            'The field must contain the value.'
        );

    if(elemValue.val() == '')
        flag = errorFieldMessage(
            elemValue, 
            'The field must contain the value.'
        );

    return flag;
}