
var div= document.getElementById("carrusel");


bucle();
function bucle(){
    div.style.backgroundImage=`url('recursos/img/resultados_cantera.jpg')`;
    setTimeout(bucle2,3500);
}
function bucle2(){
    div.style.backgroundImage=`url('recursos/img/info_entradas.jpg')`;
    setTimeout(bucle3,3500);
}
function bucle3(){
    div.style.backgroundImage=`url('recursos/img/plan_semanal.jpg')`;
    setTimeout(bucle4,3500);
}
function bucle4(){
    div.style.backgroundImage=`url('recursos/img/rayo_b_noticia.jpg')`;
    setTimeout(bucle,3500);
}