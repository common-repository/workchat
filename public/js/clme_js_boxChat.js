window.onload = function(){
    if ( document.querySelector(".clme_imgchat") != null && document.getElementById("clme_x") != null){
        document.querySelector(".clme_imgchat").onclick = function(){
            document.getElementById("clme_img").style.display='none';
            document.getElementById("clme_x").style.display='block';
        }
        document.getElementById("clme_x").onclick = function(){
            document.getElementById("clme_img").style.display='block';
            document.getElementById("clme_x").style.display='none';
        }
    }
}