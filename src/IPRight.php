<?php

namespace WorkAnyWare\IPFO;

use WorkAnyWare\IPFO\IPRightInterface;
use WorkAnyWare\IPFO\IPRights\Document;
use WorkAnyWare\IPFO\IPRights\DocumentCollection;
use WorkAnyWare\IPFO\IPRights\IPFFileHandler;
use WorkAnyWare\IPFO\Parties\Agent;
use WorkAnyWare\IPFO\Parties\Applicant;
use WorkAnyWare\IPFO\IPRights\Citation;
use WorkAnyWare\IPFO\Parties\Inventor;
use WorkAnyWare\IPFO\Parties\Party;
use WorkAnyWare\IPFO\IPRights\Priority;
use WorkAnyWare\IPFO\IPRights\RightType;
use WorkAnyWare\IPFO\IPRights\SearchSource;

class IPRight implements IPRightInterface
{
    /** @var RightType  */
    private $rightType;
    private $applicationDate;
    private $applicationCountry;
    private $applicationNumber;

    private $publicationDate;
    private $publicationCountry;
    private $publicationNumber;

    private $grantDate;
    private $grantCountry;
    private $grantNumber;

    private $designatedStates;

    private $status;

    /** @var Party */
    private $clients;

    /** @var Party */
    private $inventors;
    /** @var Party */
    private $applicants;

    private $titles;

    private $citations = [];

    private $priorities = [];

    private $languageOfFiling;

    /** @var  SearchSource */
    private $source;

    /** @var Party */
    private $agents;

    private $custom = [];

    /** @var DocumentCollection */
    private $documents = [];

    /**
     * IPRight constructor.
     *
     * @param RightType $rightType
     */
    public function __construct($rightType = RightType::PATENT)
    {
        $this->applicants = new Party();
        $this->inventors = new Party();
        $this->agents = new Party();
        $this->clients = new Party();
        if ($rightType == RightType::PATENT) {
            $rightType = RightType::patent();
        }
        $this->setRightType($rightType);
        $this->documents = new DocumentCollection();
        $this->setSource(SearchSource::fromString('Custom'));
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getApplicationDate()
    {
        return $this->applicationDate;
    }

    /**
     * @param mixed $applicationDate
     */
    public function setApplicationDate($applicationDate)
    {
        $this->applicationDate = $applicationDate;
    }

    /**
     * @return mixed
     */
    public function getApplicationCountry()
    {
        return $this->applicationCountry;
    }

    /**
     * @param mixed $applicationCountry
     */
    public function setApplicationCountry($applicationCountry)
    {
        $this->applicationCountry = $applicationCountry;
    }

    /**
     * @return mixed
     */
    public function getApplicationNumber()
    {
        return $this->applicationNumber;
    }

    /**
     * @param mixed $applicationNumber
     */
    public function setApplicationNumber($applicationNumber)
    {
        $this->applicationNumber = $applicationNumber;
    }

    /**
     * @return mixed
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return mixed
     */
    public function getPublicationCountry()
    {
        return $this->publicationCountry;
    }

    /**
     * @param mixed $publicationCountry
     */
    public function setPublicationCountry($publicationCountry)
    {
        $this->publicationCountry = $publicationCountry;
    }

    /**
     * @return mixed
     */
    public function getPublicationNumber()
    {
        return $this->publicationNumber;
    }

    /**
     * @param mixed $publicationNumber
     */
    public function setPublicationNumber($publicationNumber)
    {
        $this->publicationNumber = $publicationNumber;
    }

    /**
     * @return mixed
     */
    public function getGrantDate()
    {
        return $this->grantDate;
    }

    /**
     * @param mixed $grantDate
     */
    public function setGrantDate($grantDate)
    {
        $this->grantDate = $grantDate;
    }

    /**
     * @return mixed
     */
    public function getGrantCountry()
    {
        return $this->grantCountry;
    }

    /**
     * @param mixed $grantCountry
     */
    public function setGrantCountry($grantCountry)
    {
        $this->grantCountry = $grantCountry;
    }

    /**
     * @return mixed
     */
    public function getGrantNumber()
    {
        return $this->grantNumber;
    }

    /**
     * @param mixed $grantNumber
     */
    public function setGrantNumber($grantNumber)
    {
        $this->grantNumber = $grantNumber;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return Party
     */
    public function getApplicants($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = [];
            /** @var Applicant $applicant */
            foreach ($this->applicants->getMembers() as $applicant) {
                $arrayToReturn[] = $applicant->toArray();
            }
            return $arrayToReturn;
        }
        return $this->applicants;
    }

    /**
     * @param mixed $applicants
     */
    public function setApplicants(Party $applicants)
    {
        $this->applicants = $applicants;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return Party
     */
    public function getInventors($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = [];
            /** @var Inventor $inventor */
            foreach ($this->inventors->getMembers() as $inventor) {
                $arrayToReturn[] = $inventor->toArray();
            }
            return $arrayToReturn;
        }
        return $this->inventors;
    }

    /**
     * @param mixed $inventors
     */
    public function setInventors(Party $inventors)
    {
        $this->inventors = $inventors;
    }

    public function addTitle($name, $value)
    {
        $this->titles[$name] = $value;
    }

    /**
     * @return mixed
     */
    public function getEnglishTitle()
    {
        return isset($this->titles['english']) ? $this->titles['english'] : '';
    }

    /**
     * @param mixed $englishTitle
     */
    public function setEnglishTitle($englishTitle)
    {
        $this->titles['english'] = $englishTitle;
    }

    /**
     * @return mixed
     */
    public function getFrenchTitle()
    {
        return isset($this->titles['french']) ? $this->titles['french'] : '';
    }

    /**
     * @param mixed $frenchTitle
     */
    public function setFrenchTitle($frenchTitle)
    {
        $this->titles['french'] = $frenchTitle;
    }

    /**
     * @return mixed
     */
    public function getGermanTitle()
    {
        return isset($this->titles['german']) ? $this->titles['german'] : '';
    }

    /**
     * @param mixed $germanTitle
     */
    public function setGermanTitle($germanTitle)
    {
        $this->titles['german'] = $germanTitle;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getCitations($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = '';
            /** @var Citation $citation */
            foreach ($this->citations as $citation) {
                if ($citation->getType() == Citation::PATENT) {
                    $arrayToReturn[] = [
                        'type'     => $citation->getType(),
                        'number'   => $citation->getNumber(),
                        'country'  => $citation->getCountry(),
                        'cited-by' => $citation->getCitedBy(),
                        'date'     => $citation->getCitationDate(),
                    ];
                } else {
                    $arrayToReturn[] = [
                        'type'     => $citation->getType(),
                        'text'     => $citation->getText(),
                        'country'  => $citation->getCountry(),
                        'cited-by' => $citation->getCitedBy(),
                        'date'     => $citation->getCitationDate(),
                    ];
                }
            }
            return $arrayToReturn;
        }
        return $this->citations;
    }

    public function addCitation(Citation $citation)
    {
        $this->citations[] = $citation;
    }

    /**
     * @param mixed $citations
     */
    public function setCitations(Citation ...$citations)
    {
        $this->citations = $citations;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getPriorities($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = '';
            /** @var Priority $priority */
            foreach ($this->priorities as $priority) {
                $arrayToReturn[] = [
                    'number'  => $priority->getNumber(),
                    'date'    => $priority->getDate(),
                    'country' => $priority->getCountry(),
                    'kind'    => $priority->getKind()
                ];
            }
            return $arrayToReturn;
        }
        return $this->priorities;
    }

    /**
     * @param mixed $priorities
     */
    public function setPriorities(Priority ...$priorities)
    {
        $this->priorities = $priorities;
    }

    public function addPriority(Priority $priority)
    {
        $this->priorities[] = $priority;
    }

    public function toArray($includeDocumentContent = false)
    {
        return [
            'type'             => $this->rightType->__toString(),
            'source'           => $this->getSource()->__toString(),
            'status'           => $this->getStatus(),
            'titles'           => $this->titles,
            'application'      => [
                'country' => $this->getApplicationCountry(),
                'date'    => $this->getApplicationDate(),
                'number'  => $this->getApplicationNumber()
            ],
            'publication'      => [
                'country' => $this->getPublicationCountry(),
                'date'    => $this->getPublicationDate(),
                'number'  => $this->getPublicationNumber()
            ],
            'grant'            => [
                'country' => $this->getGrantCountry(),
                'date'    => $this->getGrantDate(),
                'number'  => $this->getGrantNumber()
            ],
            'priorities'       => $this->getPriorities(true),
            'applicants'       => $this->getApplicants(true),
            'inventors'        => $this->getInventors(true),
            'clients'          => $this->getClients(true),
            'citations'        => $this->getCitations(true),
            'languageOfFiling' => $this->getLanguageOfFiling(),
            'agents'           => $this->getAgents(true),
            'designatedStates' => $this->getDesignatedStates(),
            'custom'           => $this->getCustom(),
            'documents'        => $this->documents()->toArray($includeDocumentContent)
        ];
    }

    /**
     * @return mixed
     */
    public function getLanguageOfFiling()
    {
        return $this->languageOfFiling;
    }

    /**
     * @param mixed $languageOfFiling
     */
    public function setLanguageOfFiling($languageOfFiling)
    {
        $this->languageOfFiling = $languageOfFiling;
    }

    /**
     * @param $inArrayFormat
     *
     * @return Agent[]|array
     */
    public function getAgents($inArrayFormat)
    {
        if ($inArrayFormat) {
            $arrayToReturn = [];
            /** @var Agent $agent */
            foreach ($this->agents->getMembers() as $agent) {
                $arrayToReturn[] = $agent->toArray();
            }
            return $arrayToReturn;
        }
        return $this->inventors;
    }

    /**
     * @param Party $agents
     */
    public function setAgents(Party $agents)
    {
        $this->agents = $agents;
    }

    /**
     * @return mixed
     */
    public function getRightType()
    {
        return $this->rightType;
    }

    /**
     * @param mixed $rightType
     */
    public function setRightType(RightType $rightType)
    {
        $this->rightType = $rightType;
    }


    /**
     * @return mixed
     */
    public function getDesignatedStates()
    {
        return $this->designatedStates;
    }

    /**
     * @param mixed $designatedStates
     */
    public function setDesignatedStates($designatedStates)
    {
        $this->designatedStates = $designatedStates;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param bool $arrayFormat
     *
     * @return Party
     */
    public function getClients($arrayFormat = false)
    {
        if ($arrayFormat) {
            $arrayToReturn = [];
            /** @var Inventor $client */
            foreach ($this->clients->getMembers() as $client) {
                $arrayToReturn[] = $client->toArray();
            }
            return $arrayToReturn;
        }
        return $this->inventors;
    }

    /**
     * @param Party $clients
     */
    public function setClients(Party $clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return array
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param $fieldName
     * @param $value
     *
     */
    public function addCustom($fieldName, $value)
    {
        $this->custom[$fieldName] = $value;
    }

    /**
     * @return DocumentCollection
     */
    public function &documents()
    {
        return $this->documents;
    }
}
