function OnTextAreaConstruct(arParams) {
    let iInputID   = arParams.oInput.id;
    let iTextAreaID   = iInputID + '_ta';

    let obLabel   = arParams.oCont.appendChild(BX.create('textarea', {
        props : {
            'cols' : 40,
            'rows' : 5,
            'id' : iTextAreaID
        },
        html: arParams.oInput.value
    }));

    $("#"+iTextAreaID).on('keyup', function() {
        $("#"+iInputID).val($(this).val());
    });
}