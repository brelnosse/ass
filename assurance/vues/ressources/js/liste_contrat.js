const delcontratbtns = document.querySelectorAll('.liste_contrat .actionBtn .removeItem');
const deletemodal = document.querySelector('.modalContainer.del');
const rmvItembtn = document.querySelector('.modalContainer.del .deleteCont');
const cclItembtn = document.querySelector('.modalContainer.del .ccl');

for(let delcontratbtn of delcontratbtns){
    delcontratbtn.addEventListener('click', (e)=>{
        deletemodal.style.display="flex";
        rmvItembtn.id = e.target.id;
    })
}
rmvItembtn.addEventListener('click', (e)=>{
    if(e.target.id.trim() != ''){
        window.location = "liste_contrat.php?action=delete&id="+e.target.id;
    }
})
document.addEventListener('click', (e)=>{
    if(![...e.target.classList].includes('removeItem'))
        deletemodal.style.display = "none"
})
const infoAlert = document.querySelector('.info.alert');
if(infoAlert != null){
    console.log(infoAlert)
    setTimeout(()=>{
        infoAlert.style.display = "none";
    },3000);
}

const search = document.querySelector('#search')
const listeContrat = document.querySelector('.liste_contrat');
const lastlisteContratLine = document.querySelector('.liste_contrat tbody tr:last-of-type')
const tablelines = document.querySelectorAll(".liste_contrat tbody tr.table-line");

search.addEventListener('input', (e)=>{
    let hiddenLines;
    if(tablelines.length != 0){
        hiddenLines= document.querySelectorAll(".liste_contrat tbody tr.table-line.hide");
        if(hiddenLines.length === tablelines.length){
            lastlisteContratLine.classList.remove('hide')
        }else{
            lastlisteContratLine.classList.add('hide')
        }
        if(e.target.value.trim() != ""){
            for(let tableline of tablelines){
                const reference = tableline.querySelector('td:nth-child(1) span').textContent.trim().toLowerCase();
                const libelle = tableline.querySelector('td:nth-child(2) span').textContent.trim().toLowerCase();
                const searchValue = e.target.value.trim().toLowerCase();
                
                if(!reference.includes(searchValue) && !libelle.includes(searchValue)){
                    tableline.classList.add('hide');
                }else{
                    lastlisteContratLine.classList.add('hide');
                    tableline.classList.remove('hide');
                }
            }
        }else{
            for(let tableline of tablelines){
                tableline.classList.remove('hide')
            }        
            lastlisteContratLine.classList.add('hide')
        }
    }
});