<?php

require_once 'Model/District.php';
require_once 'Model/Candidate.php';
require_once 'Dao/CandidateDao.php';

class DistrictController {

    private $districtsNames;
    private $districts;

    public function __construct() {
        $this->districtsNames = array(
            1 => "საარჩევნო ოლქი #1: მთაწმინდისა და კრწანისის რაიონები",
            2 => "საარჩევნო ოლქი #2: ვაკის რაიონი",
            3 => "საარჩევნო ოლქი #3: საბურთალოს რაიონი",
            4 => "საარჩევნო ოლქი #4: ისნის რაიონი",
            5 => "საარჩევნო ოლქი #5: სამგორის რაიონი",
            6 => "საარჩევნო ოლქი #6: დიდუბისა და ჩუღურეთის რაიონები",
            7 => "საარჩევნო ოლქი #7: გლდანის რაიონი",
            8 => "საარჩევნო ოლქი #8: ნაძალადევის რაიონი",
            9 => "საარჩევნო ოლქი #9: თელავის, ახმეტის, ყვარლისა და ლაგოდეხის მუნიციპალიტეტები",
            10 => "საარჩევნო ოლქი #10: გურჯაანის, საგარეჯოს, დედოფლისწყაროსა და სიღნაღის მუნიციპალიტეტები",
            11 => "საარჩევნო ოლქი #11: რუსთავის მუნიციპალიტეტი და გარდაბნის 10 ადმინისტრაციული ერთეულები",
            12 => "საარჩევნო ოლქი #12: მარნეულის მუნიციპალიტეტი და გარდაბნის  მუნიციპალიტეტის ნაწილი",
            13 => "საარჩევნო ოლქი #13: ბოლნისის, დმანისის, თეთრიწყაროსა და წალკის მუნიციპალიტეტები",
            14 => "საარჩევნო ოლქი #14: მცხეთის, დუშეთის, თიანეთისა და ყაზბეგის მუნიციპალიტეტები;",
            15 => "საარჩევნო ოლქი #15: კასპის მუნიციპალიტეტი და გორის მუნიციპალიტეტის ნაწილი",
            16 => "საარჩევნო ოლქი #16: ხაშურისა და ქარელის მუნიციპალიტეტები და გორის მუნიციპალიტეტის ნაწილი",
            17 => "საარჩევნო ოლქი #17: ახალციხის, ბორჯომის, ადიგენისა და ასპინძის მუნიციპალიტეტები",
            18 => "საარჩევნო ოლქი #18: ახალქალაქისა და ნინოწმინდის მუნიციპალიტეტები",
            19 => "საარჩევნო ოლქი #19: ქუთაისის მუნიციპალიტეტი",
            20 => "საარჩევნო ოლქი #20: საჩხერის, ჭიათურისა და ხარაგაულის მუნიციპალიტეტები",
            21 => "საარჩევნო ოლქი #21: ტყიბულის, თერჯოლის, ზესტაფონისა და ბაღდათის მუნიციპალიტეტები",
            22 => "საარჩევნო ოლქი #22: სამტრედიის, წყალტუბოს, ვანისა და ხონის მუნიციპალიტეტები",
            23 => "საარჩევნო ოლქი #23: ზუგდიდის მუნიციპალიტეტი",
            24 => "საარჩევნო ოლქი #24: ფოთის, ხობისა და სენაკის მუნიციპალიტეტები",
            25 => "საარჩევნო ოლქი #25: წალენჯიხის, ჩხოროწყუს, მარტვილისა და აბაშის მუნიციპალიტეტები",
            26 => "საარჩევნო ოლქი #26: ოზურგეთის, ლანჩხუთისა და ჩოხატაურის მუნიციპალიტეტები",
            27 => "საარჩევნო ოლქი #27: ბათუმის მუნიციპალიტეტი",
            28 => "საარჩევნო ოლქი #28: ქობულეთის მუნიციპალიტეტი და ხელვაჩაურის მუნიციპალიტეტის ნაწილი",
            29 => "საარჩევნო ოლქი #29: ხელვაჩაურის მუნიციპალიტეტის ნაწილი, ქედის, შუახევისა და ხულოს მუნიციპალიტეტები",
            30 => "საარჩევნო ოლქი #30: ამბროლაურის, ონის, ცაგერის, ლენტეხისა და მესტიის მუნიციპალიტეტები",
            31 => "თბილისი",
            32 => "აფხაზეთი-რუსეთის მიერ ოკუპირებული რეგიონი",
            33 => "შიდა ქართლის რუსეთის მიერ  ოკუპირებული ტერიტორიები",
        );
        $this->districts = array(
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
            7 => null,
            8 => null,
            9 => null,
            10 => null,
            11 => null,
            12 => null,
            13 => null,
            14 => null,
            15 => null,
            16 => null,
            17 => null,
            18 => null,
            19 => null,
            20 => null,
            21 => null,
            22 => null,
            23 => null,
            24 => null,
            25 => null,
            26 => null,
            27 => null,
            28 => null,
            29 => null,
            30 => null,
            31 => null,
            32 => null,
            33 => null
        );

        $this->nameDistricts();
        $this->fillCandidates();
        $this->fillSuperDistricts();
    }

    private function nameDistricts() {
        for ($x = 1; $x < count($this->districts) + 1; $x++) {
            $district = new District();
            $district->setDistrictId($x);
            $district->setDistrictFullName($this->districtsNames[$x]);
            $this->districts[$x] = $district;
        }
    }

    private function fillCandidates() {
        $candidateDao = new CandidateDao();
        $candidates = $candidateDao->getCandidates();

        foreach ($candidates as $candidate) {
            $districtId = $candidate->getDistrictId();
            $district = $this->districts[$districtId];
            $districtCandidates = $district->getDistrictCandidates();
            array_push($districtCandidates, $candidate);
            $district->setDistrictCandidates($districtCandidates);
            $this->districts[$districtId] = $district;
        }
    }

    private function fillSuperDistricts() {
        $district = $this->districts[31];
        $district->setMessage("თბილისის საარჩევნო ოლქების სანახავად დააწკაპუნე ");
        $this->districts[31] = $district;

        $district = $this->districts[32];
        $district->setMessage("ოკუპაციის გამო ამ ტერიტორიაზე არჩევნები არ ტარდება");
        $this->districts[32] = $district;

        $district = $this->districts[33];
        $district->setMessage("ოკუპაციის გამო ამ ტერიტორიაზე არჩევნები არ ტარდება");
        $this->districts[33] = $district;
    }

    public function getDistricts() {
        return $this->districts;
    }

}
