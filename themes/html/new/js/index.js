
function scrollDoor(){ 
} 
scrollDoor.prototype = { 
    sd : function(menus,divs,openClass,closeClass){ 
        var _this = this; 
        if(menus.length != divs.length) 
        { 
            alert("菜单层数量和内容层数量不一样!"); 
            return false; 
        }                 
        for(var i = 0 ; i < menus.length ; i++) 
        {     
            _this.$(menus[i]).value = i;                 
            _this.$(menus[i]).onclick = function(){ 
                     
                for(var j = 0 ; j < menus.length ; j++) 
                {                         
                    _this.$(menus[j]).className = closeClass; 
                    _this.$(divs[j]).style.display = "none"; 
                } 
                _this.$(menus[this.value]).className = openClass;     
                _this.$(divs[this.value]).style.display = "block";                 
            } 
        } 
        }, 
    $ : function(oid){ 
        if(typeof(oid) == "string") 
        return document.getElementById(oid); 
        return oid; 
    } 
} 
window.onload = function(){ 
    var SDmodel = new scrollDoor(); 
    SDmodel.sd(["m01","m02","m03","m04"],["c01","c02","c03","c04"],"sd01","sd02"); 
    SDmodel.sd(["mm01","mm02","mm03","mm04"],["cc01","cc02","cc03","cc04"],"sd01","sd02"); 
    SDmodel.sd(["mmm01","mmm02","mmm03","mmm04"],["ccc01","ccc02","ccc03","ccc04"],"sd01","sd02"); 
} // JavaScript Document