<?php
require_once 'Dao/CandidateDao.php';

class CandidateController {
    
    public function voteCandidate($candidateId){
        $candidateDao=new CandidateDao();
        $candidateDao->voteCandidate($candidateId);
        
    }
    
}
