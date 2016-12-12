/************************************************
* @Author: Mojtaba Bakhtiaree                   *
* @Creation_Date:  Thursday, January 16, 14     *
* @Desc: Select a bank as easy as possible      *
*************************************************/

$.fn.bankSelector = function() {
    var self = $(this);
    console.log(self);
    var HTML_STREAM  = $("<div class=\"bank_sel hide\">");
    HTML_STREAM.html("<div id=\"pointer\" class=\"triangle triangleTop\"></div><div id=\"bank_item_container\"><ul><li class=\"bank-icon bnk-saman\" data-value=\"سامان\"><span>سامان</span></li><li class=\"bank-icon bnk-agri\" data-value=\"کشاورزی\"><span>کشاورزی</span></li><li class=\"bank-icon bnk-maskan\" data-value=\"مسکن\"><span>مسکن</span></li><li class=\"bank-icon bnk-mellat\" data-value=\"ملت\"><span>ملت</span></li><li class=\"bank-icon bnk-melli\" data-value=\"ملی\"><span>ملی</span></li><li class=\"bank-icon bnk-en\" data-value=\"اقتصاد\"><span>اقتصاد</span></li><li class=\"bank-icon bnk-parsian\" data-value=\"پارسیان\"><span>پارسیان</span></li><li class=\"bank-icon bnk-sepah\" data-value=\"سپه\"><span>سپه</span></li><li class=\"bank-icon bnk-pasargad\" data-value=\"پاسارگاد\"><span>پاسارگاد</span></li><li class=\"bank-icon bnk-ansar\" data-value=\"انصار\"><span>انصار</span></li><li class=\"bank-icon bnk-karafarin\" data-value=\"کارآفرین\"><span>کارآفرین</span></li><li class=\"bank-icon bnk-refah\" data-value=\"رفاه\"><span>رفاه</span></li></ul></div>");

    var elementWidth = self.width() +
        parseInt(self.css('padding-left')) +
        parseInt(self.css('padding-right')) +
        parseInt(self.css('margin-left')) +
        parseInt(self.css('margin-right'));

    var elementHeight = self.height() +
        parseInt(self.css('padding-top')) +
        parseInt(self.css('padding-bottom')) +
        parseInt(self.css('margin-top')) +
        parseInt(self.css('margin-bottom'));

    var wrapper_ = $("<div style='position:relative;height:" + elementHeight + "px" + ";width:" + elementWidth +  "px;'>");

    wrapper_.append(self);
    wrapper_.append(HTML_STREAM);

    HTML_STREAM.css("top",(self.height() + 3) + "px") ;
    HTML_STREAM.css("left",- (wrapper_.find(".bank_sel").width() - self.width()) / 2  + "px") ;

    HTML_STREAM.css("button","-8px");

    console.log(self.parent());
    self.parent().append(wrapper_);

    //Hooks
    self.bind("click",function(Evenet) {
        Evenet.stopPropagation();
        if(wrapper_.find(".bank_sel").hasClass("hide")){
            wrapper_.find(".bank_sel").removeClass("hide");
        }else{
            wrapper_.find(".bank_sel").addClass("hide");
        }
    });

    $("body").bind("click",function(){
        if(!$(".bank_sel").hasClass("hide")){
            $(".bank_sel").addClass("hide");
        }
    });


    $(".bank_sel").bind("click",function(Event) {
        Event.stopPropagation();
    });

    $("#bank_item_container li").bind("click",function() {
        self.val($(this).attr("data-value"));
        $(".bank_sel").addClass("hide");
    });
};