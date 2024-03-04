jQuery(function($) {
    get_font();

    $("#btn_search_font").click(function (e) { 
        e.preventDefault();
        $( "#search_font" ).val("");
        get_font();
    });
    var onloadarrFonts = true;
    var arrFonts = [];
    
    function get_font(s = ""){
        
        $('#response_font').html('<div></div><div class="loadingfont"><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="64px" height="64px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M75.4 126.63a11.43 11.43 0 0 1-2.1-22.65 40.9 40.9 0 0 0 30.5-30.6 11.4 11.4 0 1 1 22.27 4.87h.02a63.77 63.77 0 0 1-47.8 48.05v-.02a11.38 11.38 0 0 1-2.93.37z" fill="#5bc500"/><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1800ms" repeatCount="indefinite"></animateTransform></g></svg></div><div></div>');
        var temparrFonts = [];
        var url = "https://www.googleapis.com/webfonts/v1/webfonts?capability=WOFF2&key="+variable.key_gfont;
        if(s!=""){
            url += s;
        }
        $.ajax({url: url, 

            success: function(result){
                setTimeout(() => {
                    
                
                $('#response_font').html("");
                result.items.forEach(element => {

                    //console.log(element.variants.filter(o =>  !o.includes('italic')));
                    if(onloadarrFonts){
                        arrFonts.push(element.family);
                    }
                    var family = element.family.replace(/ /g, "+");
                    
                    var categfont = $('#categfont').find(":selected").val();
                    
                    var item = '<div class="list-group-item" data-weight="'+element.variants.filter(o =>  !o.includes('italic')).toString()+'" data-qryfont="'+family+'" data-font="'+element.family+'" data-click="0">'+element.family+'<div class="preview_font" style="font-family: '+element.family+'">'+$('#text_preview_font').val()+'</div></div>';
                    //console.log(categfont)
                    if(categfont == "all"){
                        $('#response_font').append(item);
                    }else{
                        if(element.category == categfont){
                            $('#response_font').append(item);
                        }
                    }
                });
                isScrolledIntoView();
                }, 1000);
            },
            error:function(){
                alert("Errore");
            }
        });
        onloadarrFonts = false;
    }
    
    
    $('#response_font').on('scroll', function() {
        isScrolledIntoView();
    });

    $('#categfont').change(function (e) { 
        e.preventDefault();
        get_font();
    });


    $("#response_font").delegate(".list-group-item","click", function(e){
        
        if($('#response_action .list-group-item.'+$(this).data('font').replace(/ /g, "_")).length == 0){
            var htmlres = '';
            htmlres += '<div class="list-group-item '+$(this).data('font').replace(/ /g, "_")+'" data-font="'+$(this).data('font')+'" >';
            htmlres +=      '<div>';
            htmlres +=          '<strong>'+$(this).data('font')+'</strong>';
            htmlres +=          '<div class="preview_font" style="font-family: '+$(this).data('font')+'">'+$('#text_preview_font').val()+'</div><br>';
            htmlres +=          '<div>';
            htmlres +=              '<input type="checkbox" checked name="bc_gfonts['+$(this).data('font')+']" class="chk_font" value="'+$(this).data('font')+'">';
            htmlres +=              '<input type="hidden" name="bc_gfonts['+$(this).data('font')+'][bc_gfonts_weight]" value="'+$(this).data('weight')+'">';
                                if($(this).data('weight').toString().includes(',')){
                                    $(this).data('weight').split(',').forEach(element => {
                                        htmlres += '<label class="lbl_weight"><input type="checkbox"';
                                        if(element=="regular"){
                                            htmlres += ' checked ';
                                        }
                                        htmlres += 'name="bc_gfonts['+$(this).data('font')+'][chk_weight][]" data-font="'+$(this).data('font')+'" data-qryfont="'+$(this).data('qryfont')+'" class="chk_weight" value="'+element+'"><span>'+element.toString().replace(/regular/g, "400")+'</span></label>';
                                        
                                    });
                                }else{
                                    var element = $(this).data('weight');
                                    htmlres += '<label class="lbl_weight"><input type="checkbox" checked name="bc_gfonts['+$(this).data('font')+'][chk_weight][]" data-font="'+$(this).data('font')+'" data-qryfont="'+$(this).data('qryfont')+'" class="chk_weight" value="'+element+'"><span>'+element.toString().replace(/regular/g, "400")+'</span></label>';
                                }
            htmlres +=          '</div>';
            htmlres +=      '</div>';
            htmlres +=      '<div>';
            htmlres +=          '<a href="#" class="remove_font button-secondary delete"><span class="dashicons dashicons-trash" style="vertical-align: text-top;"></span> Rimuovi</a>';
            htmlres +=      '</div>';
            htmlres += '</div>';
            $('#response_action').append(htmlres);
        
        }

    });
    $("#response_action").delegate(".remove_font","click", function(e){
        //$(this).remove();
        e.preventDefault();
        $(this).parents('.list-group-item').remove();
    });
    
    $( "#search_font" ).autocomplete({
        source: arrFonts,
        
        minLength: 2,
        response: function( event, ui ) {
            var s = "";
            ui.content.forEach(element => {
                //console.log(element.value);
                s += "&family="+element.value
            });
            get_font(s);
        },
            

    } ).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "" );
    };

    $("#response_action .list-group-item").each(function (index, element) {
        var family = $(this).data('font').replace(/ /g, "+");
        var css = "@import url('https://fonts.googleapis.com/css2?family="+family+":wght@"+$(this).data('weight').replace(/regular/g, "400").replace(/,/g, ";");+"');";
        //console.log($(this).data());
        $('<style/>').append(css).appendTo(document.head);
    
    });

    $("#response_action").delegate(".lbl_weight","mouseover", function(e){ 
        e.preventDefault(); 
        $(this).parents('.list-group-item').find('.preview_font').css('font-weight',$('.chk_weight',this).val().replace(/regular/g, "400"));
    });

    function isScrolledIntoView(){
        $("#response_font .list-group-item").each(function (index, element) {
            const rect = element.getBoundingClientRect();
            var family = $(element).data('qryfont');
            if (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            ){
                var css = "@import url('https://fonts.googleapis.com/css2?family="+family+":wght@"+$(element).data('weight').toString().replace(/regular/g, "400").replace(/,/g, ";");+"');";
                if($(element).data('click')==0){
                    $('<style/>').addClass($(element).data('font').replaceAll(/ /g,"")).append(css).appendTo(document.head);
                    $(element).data('click',1);
                }
                $(".preview_font",element).css("opacity", "1");
            }else{
                $('style.'+$(element).data('font').replaceAll(/ /g,""),document.head).remove();
                $(element).data('click',0);
                $(".preview_font",element).css("opacity", "0");
            }

            /*var parentPos = $('#response_font').offset();
            var childPos = $(element).position();
            var family = $(element).data('qryfont');
            if( ($('#response_font').height()-childPos.top+200) >= 0) {
                
                var css = "@import url('https://fonts.googleapis.com/css2?family="+family+":wght@"+$(element).data('weight').toString().replace(/regular/g, "400").replace(/,/g, ";");+"');";
                if($(element).data('click')==0){
                    $('<style/>').addClass(family).append(css).appendTo(document.head);
                    $(element).data('click',1);
                }
                $(".preview_font",element).css("opacity", "1");
            }
            */

                //$('style.'+family,document.head).remove();
            
        });
    }

    $(".preview_font").text($('#text_preview_font').val());
    $('#text_preview_font').keyup(function (e) {
        $(".preview_font").text($('#text_preview_font').val());
    });

    $(".btn_chiaroscuro").click(function (e) { 
        e.preventDefault();
        $(this).toggleClass('dark');
        $("#main_fonts").toggleClass('dark');
    });
});


