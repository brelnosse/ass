//menu horizontal
const toggleVerticalMenu = document.querySelector(".toggle-verticalMenu");
const horizontalMenu = document.querySelector(".horizontal-navbar");
const verticalMenu = document.querySelector(".vertical-menu");
let verticalMenuWidth = parseInt(getComputedStyle(verticalMenu).getPropertyValue('width'));
let horizontalMenuWidth = parseInt(getComputedStyle(horizontalMenu).getPropertyValue('width'));
let container = document.querySelector(".container");
let containerWidth = parseInt(getComputedStyle(container).getPropertyValue('width'));
toggleVerticalMenu.addEventListener('click', (e)=>{
    verticalMenu.classList.toggle('hide');
    if(![...verticalMenu.classList].includes('hide')){
        toggleVerticalMenu.classList.add('active');
        container.style.marginLeft = "280px";
        container.style.width = "calc(100% - 280px)"
    }else{
        toggleVerticalMenu.classList.remove('active');
        container.style.marginLeft = "0px";
        container.style.width = "100%"
    }
})
//menu vertical

const toggleVerticalItems = document.querySelectorAll(".v-menu-item-title");
const verticalDropdowns = document.querySelectorAll(".v-menu-item-menu");

for(let toggleVerticalItem of toggleVerticalItems){
    toggleVerticalItem.addEventListener('click', (e)=>{
        if(e.target.nextElementSibling !== null){
            // for(let verticalDropdown of verticalDropdowns){
            //     if(e.target.nextElementSibling.id != verticalDropdown.id) verticalDropdown.classList.remove('show');
            // }
            console.log(e.target)
            e.target.nextElementSibling.classList.toggle('show');
//             e.target.lastElementChild.classList.toggle('rotate');
        } 
    })    
}
onload = function(){
    if(this.innerWidth <= 768){
        if(![...verticalMenu.classList].includes('hide')){
            verticalMenu.classList.toggle('hide');
            container.style.marginLeft = "0px";
            container.style.width = "100%"
        }
    }    
}
onresize = function(){
    if(this.innerWidth <= 768){
        if(![...verticalMenu.classList].includes('hide')){
            verticalMenu.classList.toggle('hide');
            container.style.marginLeft = "0px";
            container.style.width = "100%"
        }
    }
}
function checkage(){
    const dateNaiss = document.querySelector("#date_naissance");
    if(dateNaiss != null){
        const dateActu = new Date();
        const dateNaissVal = new Date(dateNaiss.value);
        let defaultDate = ''
        let year = dateActu.getFullYear() - dateNaissVal.getFullYear();
        if(year < 18){
            defaultDate += (dateActu.getFullYear() - 18) + '-12-31';
            dateNaiss.value = defaultDate;
            dateNaiss.max = defaultDate;
        }
        dateNaiss.onchange = function(){
            year = dateActu.getFullYear() - dateNaissVal.getFullYear();
            if(year < 18){
                defaultDate += (dateActu.getFullYear() - 18) + '-12-31';
                dateNaiss.value = defaultDate;
                dateNaiss.max = defaultDate;
            }
        }
    }
}
function locksubmittingdate(){
    const submittingDate = document.querySelector("#date_effet_souhaitee");
    if(submittingDate != null){
        let month = '';
        let date = new Date();
        if(parseInt(date.getMonth()+1)%10 == parseInt(date.getMonth()+1)){
            month = '0'+parseInt(date.getMonth()+1);
        }else{
            month = parseInt(date.getMonth()+1);
        }

        submittingDate.value = date.getFullYear()+'-'+month+'-'+date.getDate();
        submittingDate.min = date.getFullYear()+'-'+month+'-'+(parseInt(date.getDate())+1);
    }
}
function lockdateValidation(){
    const dateValidation = document.querySelector("#date_validation");
    if(dateValidation != null){
        let month = '';
        let date = new Date();
        if(parseInt(date.getMonth()+1)%10 == parseInt(date.getMonth()+1)){
            month = '0'+parseInt(date.getMonth()+1);
        }else{
            month = parseInt(date.getMonth()+1);
        }   
        dateValidation.value = date.getFullYear()+'-'+month+'-'+date.getDate();
        dateValidation.min = date.getFullYear()+'-'+month+'-'+date.getDate();
    }
}
function checkcontrat(){
    const bdate = document.querySelector("#bdate");
    const edate = document.querySelector("#edate");
    if(bdate != null && edate != null){
        function checkbdate(){
            if(bdate != null){
                let month = '';
                let date = new Date();
                if(parseInt(date.getMonth()+1)%10 == parseInt(date.getMonth()+1)){
                    month = '0'+parseInt(date.getMonth()+1);
                }else{
                    month = parseInt(date.getMonth()+1);
                }
        
                bdate.value = date.getFullYear()+'-'+month+'-'+date.getDate();
                bdate.min = date.getFullYear()+'-'+month+'-'+date.getDate();
                edate.value = bdate.value;
                edate.min = bdate.value;
            }     
        }
        bdate.onchange = function(e){
            let bd = new Date(e.target.value)
            let ed = new Date(edate.value);
            if((ed.getTime() - bd.getTime()) < 0){
                edate.value = bdate.value;
            }
            edate.min = bdate.value;
        }
        checkbdate()
    }
}
checkcontrat();
lockdateValidation()
locksubmittingdate();
checkage();