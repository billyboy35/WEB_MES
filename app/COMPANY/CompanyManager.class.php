<?php

namespace App\COMPANY;
use \PDO;

class CompanyManager
{
  private $_db; // Instance de PDO

  public function __construct($db)
  {
    $this->setDb($db);
  }

  public function getDb()
  {
    $q = $this->_db->query('SELECT NAME,
                                  ADDRESS,
                                  CITY,
                                  ZIPCODE,
                                  REGION,
                                  COUNTRY,
                                  PHONE_NUMBER,
                                  MAIL,
                                  WEB_SITE,
                                  FACEBOOK_SITE,
                                  TWITTER_SITE,
                                  LKD_SITE,
                                  LOGO,
                                  SIREN,
                                  APE,
                                  TVA_INTRA,
                                  TAUX_TVA,
                                  CAPITAL,
                                  RCS
                                 FROM '. TABLE_ERP_COMPANY .'');

    $donnees = $q->fetch(PDO::FETCH_ASSOC); 
    return   $donnees;
  }

  public function updateDb(Company $Company)
  {                          
    $q = $this->_db->prepare('UPDATE '. TABLE_ERP_COMPANY .' SET NAME = :UpdateCompanyName,
                                                            ADDRESS = :UpdateCompanyAddress,
                                                            CITY = :UpdateCompanyCity,
                                                            ZIPCODE = :UpdateCompanyZipCode,
                                                            REGION = :UpdateCompanyRegion,
                                                            COUNTRY = :UpdateCompanyCountry,
                                                            PHONE_NUMBER = :UpdateCompanyPhone,
                                                            MAIL = :UpdateCompanyMail,
                                                            WEB_SITE = :UpdateCompanyMail,
                                                            FACEBOOK_SITE = :UpdateCompanyFbSite,
                                                            TWITTER_SITE = :UpdateCompanyTwitter,
                                                            LKD_SITE = :UpdateCompanyLkd,
                                                            SIREN = :UpdateCompanySIREN,
                                                            APE = :UpdateCompanyAPE,
                                                            TVA_INTRA = :UpdateCompanyTVAINTRA,
                                                            TAUX_TVA = :UpdateCompanyTAUXTVA,
                                                            CAPITAL = :UpdateCompanyCAPITAL,
                                                            RCS = :UpdateCompanyRCS');

    $q->bindValue(':UpdateCompanyName', $Company->CompanyName(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyAddress', $Company->CompanyAddress(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyCity', $Company->CompanyCity(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyZipCode', $Company->CompanyZipCode(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyRegion', $Company->CompanyRegion(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyCountry', $Company->CompanyCountry(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyPhone', $Company->CompanyPhone(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyMail', $Company->CompanyMail(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyWebSite', $Company->CompanyWebSite(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyFbSite', $Company->CompanyFbSite(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyTwitter', $Company->CompanyTwitter(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyLkd', $Company->CompanyLkd(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanySIREN', $Company->CompanySIREN(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyAPE', $Company->CompanyAPE(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyTVAINTRA', $Company->CompanyTVAINTRA(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyTAUXTVA', $Company->CompanyTAUXTVA(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyCAPITAL', $Company->CompanyCAPITAL(), PDO::PARAM_STR );
    $q->bindValue(':UpdateCompanyRCS', $Company->CompanyRCS(), PDO::PARAM_STR );

    $q->execute();
  }

  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}