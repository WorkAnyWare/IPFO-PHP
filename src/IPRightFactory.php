<?php

namespace WorkAnyWare\IPFO;

use WorkAnyWare\IPFO\IPRight;
use WorkAnyWare\IPFO\Parties\Agent;
use WorkAnyWare\IPFO\Parties\Applicant;
use WorkAnyWare\IPFO\Parties\Inventor;
use WorkAnyWare\IPFO\Parties\Party;
use WorkAnyWare\IPFO\Parties\PartyMember;
use WorkAnyWare\IPFO\IPRights\Citation;
use WorkAnyWare\IPFO\IPRights\Priority;
use WorkAnyWare\IPFO\IPRights\RightType;
use WorkAnyWare\IPFO\IPRights\SearchSource;

class IPRightFactory
{

    public static function fromJSON($jsonString)
    {
        return self::fromArray(json_decode($jsonString, true));
    }
    /**
     * Creates an IPRight From an associative array
     *
     * @param array $details
     *
     * @return IPRight
     */
    public static function fromArray(array $details)
    {
        $IPRight = new IPRight();
        $IPRight->setSource(SearchSource::fromString($details['source']));
        $IPRight->setRightType(RightType::fromString($details['type']));
        $IPRight->setLanguageOfFiling($details['languageOfFiling']);

        //Titles
        $IPRight->setEnglishTitle($details['titles']['english']);
        $IPRight->setFrenchTitle($details['titles']['french']);
        $IPRight->setGermanTitle($details['titles']['german']);

        //Application Details
        $IPRight->setApplicationCountry($details['application']['country']);
        $IPRight->setApplicationDate($details['application']['date']);
        $IPRight->setApplicationNumber($details['application']['number']);

        //Publication Details
        $IPRight->setPublicationCountry($details['publication']['country']);
        $IPRight->setPublicationDate($details['publication']['date']);
        $IPRight->setPublicationNumber($details['publication']['number']);

        //Grant Details
        $IPRight->setGrantCountry($details['grant']['country']);
        $IPRight->setGrantDate($details['grant']['date']);
        $IPRight->setGrantNumber($details['grant']['number']);

        if (is_array($details['priorities'])) {
            foreach ($details['priorities'] as $priorityArray) {
                $priority = Priority::fromNumber($priorityArray['number']);
                $priority->setDate($priorityArray['date']);
                $priority->setCountry($priorityArray['country']);
                $priority->setKind($priorityArray['kind']);
                $IPRight->addPriority($priority);
            }
        }

        //Applicants
        $applicantParty = new Party();
        if (is_array($details['applicants'])) {
            foreach ($details['applicants'] as $applicantArray) {
                $applicantParty->addMember(self::partyMemberToObject(new Applicant(), $applicantArray));
            }
        }
        $IPRight->setApplicants($applicantParty);

        //Inventors
        $inventorParty = new Party();
        if (is_array($details['inventors'])) {
            foreach ($details['inventors'] as $inventorArray) {
                $inventorParty->addMember(self::partyMemberToObject(new Inventor(), $inventorArray));
            }
        }
        $IPRight->setInventors($inventorParty);

        //Agents
        $agentParty = new Party();
        if (is_array($details['agents'])) {
            foreach ($details['agents'] as $agentArray) {
                $agentParty->addMember(self::partyMemberToObject(new Agent(), $agentArray));
            }
        }
        $IPRight->setAgents($agentParty);

        //Citations
        if (is_array($details['citations'])) {
            foreach ($details['citations'] as $citationArray) {
                if ($citationArray['type'] == Citation::PATENT) {
                    $citation = Citation::patent($citationArray['number'], $citationArray['country'],
                        $citationArray['cited-by'], $citationArray['date']);
                } else {
                    $citation = Citation::nonPatentLiterature($citationArray['text'], $citationArray['cited-by'],
                        $citationArray['country'], $citationArray['date']);
                }
                $IPRight->addCitation($citation);
            }
        }
        return $IPRight;
    }

    private static function partyMemberToObject(PartyMember $member, $memberArray)
    {
        $member->setName($memberArray['name']);
        $member->setSequence($memberArray['sequence']);
        $member->setReference($memberArray['sequence']);
        $member->setEmail($memberArray['email']);
        $member->setPhone($memberArray['phone']);
        $member->setFax($memberArray['fax']);
        $member->setNationality($memberArray['nationality']);
        $member->setDomicile($memberArray['domicile']);
        $member->setReference($memberArray['reference']);
        $member->getAddress()->setAddress($memberArray['address']['address']);
        $member->getAddress()->setPostCode($memberArray['address']['postCode']);
        $member->getAddress()->setCountry($memberArray['address']['country']);
        return $member;
    }
}
