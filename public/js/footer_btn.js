window.addEventListener("load", function(event) {
    let btnFooterSowrs = document.querySelector(".btn_sowrs");
    let btnFooterSowrsUp = document.querySelector(".btn_sowrs_up");
    let btnFooterOffer = document.querySelector(".btn_offer");
    let btnFooterOfferUp = document.querySelector(".btn_offer_up");
    let btnFooterCandidate = document.querySelector(".btn_candidate");
    let btnFooterCandidateUp = document.querySelector(".btn_candidate_up");
    let divFooterSowrs = document.querySelector(".sowrs_text");
    let divFooterOffer = document.querySelector(".offer_text");
    let divFooterCandidate = document.querySelector(".candidate_text_footer");
    let containerDivFooter = document.querySelector(".container_text_mini_footer");
    let blockSowrs = document.querySelector('.footer_title_sowrs');
    let blockOffer = document.querySelector('.footer_title_offer');
    let blockCandidate = document.querySelector('.footer_title_candidate');


    btnFooterSowrs.addEventListener('click', function (){
        containerDivFooter.setAttribute('style', 'display:block;');
        btnFooterSowrs.setAttribute('style', 'display:none;');
        btnFooterOffer.setAttribute('style', 'display:none;');
        btnFooterCandidate.setAttribute('style', 'display:none;');
        btnFooterSowrsUp.setAttribute('style', 'display:block;');
        divFooterSowrs.setAttribute('style', 'display:block;');
        blockOffer.setAttribute('style', 'display:none;');
        blockCandidate.setAttribute('style', 'display:none;');
        btnFooterSowrsUp.addEventListener('click', function (){
            containerDivFooter.setAttribute('style', 'display:none;');
            btnFooterSowrs.setAttribute('style', 'display:block;');
            btnFooterOffer.setAttribute('style', 'display:block;');
            btnFooterCandidate.setAttribute('style', 'display:block;');
            blockOffer.setAttribute('style', 'display:flex;');
            blockCandidate.setAttribute('style', 'display:flex;');
            btnFooterSowrsUp.setAttribute('style', 'display:none;');
            divFooterSowrs.setAttribute('style', 'display:none;');
        });
    });

    btnFooterOffer.addEventListener('click', function (){
        containerDivFooter.setAttribute('style', 'display:block;');
        btnFooterSowrs.setAttribute('style', 'display:none;');
        btnFooterOffer.setAttribute('style', 'display:none;');
        btnFooterCandidate.setAttribute('style', 'display:none;');
        btnFooterOfferUp.setAttribute('style', 'display:block;');
        blockSowrs.setAttribute('style', 'display:none;');
        blockCandidate.setAttribute('style', 'display:none;');
        divFooterOffer.setAttribute('style', 'display:block;');
            btnFooterOfferUp.addEventListener('click', function (){
            containerDivFooter.setAttribute('style', 'display:none;');
            btnFooterSowrs.setAttribute('style', 'display:block;');
            btnFooterOffer.setAttribute('style', 'display:block;');
            btnFooterCandidate.setAttribute('style', 'display:block;');
            blockSowrs.setAttribute('style', 'display:flex;');
            blockCandidate.setAttribute('style', 'display:flex;');
            btnFooterOfferUp.setAttribute('style', 'display:none;');
            divFooterOffer.setAttribute('style', 'display:none;');
        });
    });

    btnFooterCandidate.addEventListener('click', function (){
        containerDivFooter.setAttribute('style', 'display:block;');
        btnFooterSowrs.setAttribute('style', 'display:none;');
        btnFooterOffer.setAttribute('style', 'display:none;');
        btnFooterCandidate.setAttribute('style', 'display:none;');
        btnFooterCandidateUp.setAttribute('style', 'display:block;');
        blockSowrs.setAttribute('style', 'display:none;');
        blockOffer.setAttribute('style', 'display:none;');
        divFooterCandidate.setAttribute('style', 'display:block;');
            btnFooterCandidateUp.addEventListener('click', function (){
            containerDivFooter.setAttribute('style', 'display:none;');
            btnFooterSowrs.setAttribute('style', 'display:block;');
            btnFooterOffer.setAttribute('style', 'display:block;');
            btnFooterCandidate.setAttribute('style', 'display:block;');
            btnFooterCandidateUp.setAttribute('style', 'display:none;');
            blockSowrs.setAttribute('style', 'display:flex;');
            blockOffer.setAttribute('style', 'display:flex;');
            divFooterCandidate.setAttribute('style', 'display:none;');
        });
    });
});
