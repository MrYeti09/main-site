tinymce.init({
    menubar: false,
    selector:'textarea.betterRichTextBox',
    min_height: 600,
    resize: 'vertical',

    plugins: 'link, image, code, table, lists',
    extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
    file_browser_callback: function(field_name, url, type, win) {
        if(type =='image'){
            $('#upload_file').trigger('click');
        }
    },
    toolbar: 'styleselect |  | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code',
    convert_urls: false,
    image_caption: true,
    image_title: true,
    init_instance_callback: function (editor) {
        if (typeof tinymce_init_callback !== "undefined") {
            tinymce_init_callback(editor);
        }
    },
    setup: function (editor) {
        if (typeof tinymce_setup_callback !== "undefined") {
            tinymce_setup_callback(editor);
        }
    }
});



var allblocks = []


class block_control {
    constructor(blockId) {
        //////console.log("created block "+blockId)
        this.blockid = blockId;
        this.tabsId = [];
    }

    add_tab(tabId) {
        this.tabsId.push(tabId)
        //////console.log("the block " + this.blockid + " has the tabs " + this.tabsId)
    }
}

function findblock(name) {
    //////console.log(allblocks.length)
    for (var i = 0; i < allblocks.length; i++) {
        if (allblocks[i] != null) {

            if (allblocks[i].blockid == name) {
                return allblocks[i]
            }
        }
    }
    return null
}


function showtab(id, blockid) {
    var curb = findblock(blockid)
    //////console.log(curb,blockid)
    if (curb != null) {

        for (var i = 0; i < curb.tabsId.length; i++) {
            //////console.log(blockid)
            //////console.log(allblocks)
            //////console.log("div[tab-id=" + items[i] + "_"+blockid+"]")
            if (curb.tabsId[i] != id) {

                $("div[tab-id=" + curb.tabsId[i] + "_" + blockid + "]").hide()
                // $("div[tab-btn-id=" + items[i] + "]").css("background-color", "rgba(255, 255, 255, 0.17)");
                $("div[tab-btn-id=" + curb.tabsId[i] + "_" + blockid + "]").removeClass("tab-button-active");
                //$("div[tab-btn-id=" + items[i] + "]").addClass("tab-button");
            } else {
                //////console.log(items[i])
                $("div[tab-id=" + curb.tabsId[i] + "_" + blockid + "]").show()
                //$("div[tab-btn-id=" + items[i] + "]").removeClass("tab-button");
                $("div[tab-btn-id=" + curb.tabsId[i] + "_" + blockid + "]").addClass("tab-button-active");
            }
        }
    }
}


$('.save-this-block').on('click', function () {
    var current_block = $(this).attr('block-id');
    var required = $('input,textarea,select').filter('[required]');
    $(required).each(function () {
        var tabId = $(this).closest(".input-tab").attr('tab-id');
        if (tabId != null) {
            var tabs = tabId.split("_");
            if ($(this).val() === "" && tabs[1] === current_block) {
                showtab(tabs[0], tabs[1]);
                return false;
            }
        }
    });
});

var tabButton$ = $(".tab-button");

tabButton$.each(function () {
    //////console.log($(this)[0])
    var bl = findblock($(this).attr('block_id'))
    if (bl == null) {
        var i = new block_control($(this).attr('block_id'))
        i.add_tab($(this).attr('contained-tab'))
        allblocks.push(i)
    } else {
        bl.add_tab($(this).attr('contained-tab'))
    }
});


for (var i = 0; i < allblocks.length; i++) {
    //////console.log(allblocks[i])
    showtab(allblocks[i].tabsId[0], allblocks[i].blockid)
}

tabButton$.on('click', function (event) {
    var c = [];

    showtab($(this).attr('contained-tab'), $(this).attr('block_id'))
    // ////console.log($(this),$(this).attr('contained-tab'))
});

function random_lorem(length) {
    var result = ""
    for (var i = 0; i < length; i++) {
        if (i % 2 != 0) {
            result += " ipsun "
        } else {
            result += "lorem"
        }
    }
    return result;
}

$(".json-area").each(function () {
    var ugly = $(this).val()//document.getElementById('myTextArea').value;
    var obj = JSON.parse(ugly);
    var pretty = JSON.stringify(obj, undefined, 4);
    $(this).val(pretty);
})


function random_range(min, max) {
    return Math.floor(Math.random() * (+max - +min)) + +min
}

$(document).keydown(function (e) {
    if (e.which === 89 && e.ctrlKey) {
        random_fill()
    }
});

function random_fill() {
    $('input').each(function () {
        var type = $(this).attr('type');
        //////console.log(type);
        switch (type) {
            case 'text':
                $(this).val(random_lorem(random_range(0, 8)))
                break;
            case 'color':
                $(this).val('#' + (Math.random() * 0xFFFFFF << 0).toString(16));
                break;
            case 'number':
                $(this).val(random_range(20, 30))
                break;
        }
    })

    $('select option').each(function () {
        ////console.log($(this).attr('selected'))
        if ($(this).attr('selected') != "selected") {
            if (random_range(0, 100) > 30) {
                $(this).attr('selected', "");
            }
        } else {
            $(this).removeAttr('selected')
        }
    })

}


var items_total = [];

$(document).keydown(function(event){
    if(event.which=="17")
        cntrlIsPressed = true;
});

$(document).keyup(function(){
    cntrlIsPressed = false;
});

var cntrlIsPressed = false;


// $(".imagepicker").imagepicker({
//     hide_select: true,
//     show_label: false
// })
