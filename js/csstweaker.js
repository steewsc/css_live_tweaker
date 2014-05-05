var objCss = {files: {counter: 0, paths: {}}, content: { raw: {} }, objects: {}};

objCss.files.getAsJson = function(index){   
    if( typeof index !== "undefined" && index >= 0 ){
        return "{\""+index + "\":\"" + objCss.files.paths[index] + "\"}";
    } else {
        return JSON.stringify(objCss.files.paths);
    }
}
objCss.files.getContent = function (index){
    if( typeof index == "undefined" ){
        index = -1;
    }
    $.ajax({
        url:"functions.php",
        type:"GET",
        data:"fl=true&cssFiles="+objCss.files.getAsJson(index),
        cache:true,
        async:false,
        success:function(html){
            //\{.*\}
            objCss.content.raw = JSON.parse( html );
        }
    });
}
objCss.objects.prepare = function(){
    var counter = objCss.content.raw.counter;
    for(i=0; i < counter; i++){
        objCss.content.raw["content_" + i] = objCss.content.raw["content_" + i].replace( /\r\n/g, "" ).replace( /\n/g, "" ).replace(/\/\**.*\*\//g, "").split("}");
        var cCounter = objCss.content.raw["content_" + i].length-1;
        var currentProp;
        objCss.objects["content_" + i] = {};
        for(p = 0; p < cCounter; p++ ){
            
            currentProp = objCss.content.raw["content_" + i][p].replace( /\"/g, "'" ).split("{");
            if( currentProp[1].trim().length > 0 ){
                objCss.content.raw["content_" + i][p] = JSON.parse( "{\"" + currentProp[0].trim() + "\":" + JSON.stringify(currentProp[1].replace(/\  /g, "").split(":")).replace(/\,/g, ":").replace(/\[/g, "{").replace(/\]/g, "}").replace(/\;\"/g,"\"").replace(/\;/g,"\"\,\"").replace(/\,\"\ \"/g,"") + "}" );
                objCss.objects["content_" + i][currentProp[0].trim()] = JSON.parse( JSON.stringify(currentProp[1].replace(/\  /g, "").split(":")).replace(/\,/g, ":").replace(/\[/g, "{").replace(/\]/g, "}").replace(/\;\"/g,"\"").replace(/\;/g,"\"\,\"").replace(/\,\"\ \"/g,"") );
            } else {
                objCss.content.raw["content_" + i][p] = JSON.parse( "{\"" + currentProp[0].trim() + "\":{}}" );
                objCss.objects["content_" + i][currentProp[0].trim()] = JSON.parse( "{}" );
            }
        }
    }
}
$(document).ready(function(){
    $('[type$="text/css"]').each( function(index){
        objCss.files.paths[index] = $(this).attr("href");
        objCss.files.counter++;
    });
    objCss.files.getContent();
    objCss.objects.prepare();
    console.log(objCss);
});
