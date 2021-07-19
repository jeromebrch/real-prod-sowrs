const btnCandidat = document.querySelector("#btnCandidat");
const btnRecruteur = document.querySelector("#btnRecruteur");
const btnCandidatMini = document.querySelector("#btnCandidatMini");
const btnRecruteurMini = document.querySelector("#btnRecruteurMini");
const formCandidat = document.querySelector("form[name=candidate]");
const formRecruteur = document.querySelector("form[name=recruiter]");
const btnContactCandidate = document.querySelector("#btnContactCandidat");
const btnContactRecruiter = document.querySelector("#btnContactRecruiter");
const validateCandidatePassword = document.querySelector("#candidate_plainPassword_second");
const validateRecruiterPassword = document.querySelector("#recruiter_plainPassword_second");
const passwordCandidate = document.querySelector("#candidate_plainPassword_first");
const passwordRecruiter = document.querySelector("#recruiter_plainPassword_first");
const siretInput = document.querySelector("#recruiter_activityNumber");
const recruiterLegalStatus = document.querySelector('#recruiter_legalStatus');
let valueStatus = 0;


btnCandidat.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnRecruteur.classList.remove("btnRegisterSelected");
    formCandidat.classList.remove("d-none");
    formRecruteur.classList.add("d-none");
    validateCandidatePassword.addEventListener('blur', function (){
        let passwordValue = passwordCandidate.value;
        if(passwordValue !== validateCandidatePassword.value){
            alert("Attention, la vérification du mot de passe n'est pas correcte !");
        }
    })
});
btnRecruteur.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnCandidat.classList.remove("btnRegisterSelected");
    formCandidat.classList.add("d-none");
    formRecruteur.classList.remove("d-none");
});
btnCandidatMini.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnRecruteur.classList.remove("btnRegisterSelected");
    formCandidat.classList.remove("d-none");
    formRecruteur.classList.add("d-none");
});
btnRecruteurMini.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnCandidat.classList.remove("btnRegisterSelected");
    formCandidat.classList.add("d-none");
    formRecruteur.classList.remove("d-none");
});
recruiterLegalStatus.addEventListener('change', function (){
   valueStatus = recruiterLegalStatus.value;
});
//Dynamic verification for the legal status, in case of Association (number 13) or other status
siretInput.addEventListener('blur', function (){
    let regexAssoc = new RegExp('^[W][0-9]{9}$');
    let regexSIRET = new RegExp('^[0-9]{14}$');
    let myValidator = false;
    console.log(valueStatus);
    if(valueStatus === '13'){
        if(regexAssoc.test(siretInput.value)){
            myValidator = true
        }
        if(regexSIRET.test(siretInput.value)){
            myValidator = true
        }
        if(!myValidator){
            alert('Merci de vérifier votre numéro de SIRET ou votre code RNA');
        }
    }else{
        if(!regexSIRET.test(siretInput.value)){
            alert('Merci d\'indiquer un numéro de SIRET valide');
        }
    }
});
