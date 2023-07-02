<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bancos = [
            ['nombre' => "BANCO DE LA PROVINCIA DE BUENOS AIRES"],
            ['nombre' => "INDUSTRIAL AND COMMERCIAL BANK OF CHINA"],
            ['nombre' => "CITIBANK N.A."],
            ['nombre' => "BANCO DE GALICIA Y BUENOS AIRES S.A.U."],
            ['nombre' => "BANCO BBVA ARGENTINA S.A."],
            ['nombre' => "BANCO DE LA PROVINCIA DE CORDOBA S.A."],
            ['nombre' => "BANCO SUPERVIELLE S.A."],
            ['nombre' => "BANCO DE LA CIUDAD DE BUENOS AIRES"],
            ['nombre' => "BANCO PATAGONIA S.A."],
            ['nombre' => "BANCO HIPOTECARIO S.A."],
            ['nombre' => "BANCO DE SAN JUAN S.A."],
            ['nombre' => "BANCO MUNICIPAL DE ROSARIO"],
            ['nombre' => "BANCO SANTANDER RIO S.A."],
            ['nombre' => "BANCO DE SANTA CRUZ S.A."],
            ['nombre' => "BANCO DE LA PAMPA SOCIEDAD DE ECONOMÍA M"],
            ['nombre' => "BANCO DE CORRIENTES S.A."],
            ['nombre' => "BANCO PROVINCIA DEL NEUQUÉN SOCIEDAD ANÓ"],
            ['nombre' => "BRUBANK S.A.U."],
            ['nombre' => "BANCO INTERFINANZAS S.A."],
            ['nombre' => "HSBC BANK ARGENTINA S.A."],
            ['nombre' => "OPEN BANK ARGENTINA S.A."],
            ['nombre' => "JPMORGAN CHASE BANK, NATIONAL ASSOCIATIO"],
            ['nombre' => "BANCO CREDICOOP COOPERATIVO LIMITADO"],
            ['nombre' => "BANCO DE VALORES S.A."],
            ['nombre' => "BANCO ROELA S.A."],
            ['nombre' => "BANCO MARIVA S.A."],
            ['nombre' => "BNP PARIBAS"],
            ['nombre' => "BANCO PROVINCIA DE TIERRA DEL FUEGO"],
            ['nombre' => "BANCO DE LA REPUBLICA ORIENTAL DEL URUGU"],
            ['nombre' => "BANCO SAENZ S.A."],
            ['nombre' => "BANCO DE LA NACION ARGENTINA"],
            ['nombre' => "BANCO MERIDIAN S.A."],
            ['nombre' => "BANCO MACRO S.A."],
            ['nombre' => "BANCO COMAFI SOCIEDAD ANONIMA"],
            ['nombre' => "BANCO DE INVERSION Y COMERCIO EXTERIOR S"],
            ['nombre' => "BANCO PIANO S.A."],
            ['nombre' => "BANCO JULIO SOCIEDAD ANONIMA"],
            ['nombre' => "BANCO RIOJA SOCIEDAD ANONIMA UNIPERSONAL"],
            ['nombre' => "BANCO DEL SOL S.A."],
            ['nombre' => "NUEVO BANCO DEL CHACO S. A."],
            ['nombre' => "BANCO VOII S.A."],
            ['nombre' => "BANCO DE FORMOSA S.A."],
            ['nombre' => "BANCO CMF S.A."],
            ['nombre' => "BANCO DE SANTIAGO DEL ESTERO S.A."],
            ['nombre' => "BANCO DEL CHUBUT S.A."],
            ['nombre' => "BANCO INDUSTRIAL S.A."],
            ['nombre' => "NUEVO BANCO DE SANTA FE SOCIEDAD ANONIMA"],
            ['nombre' => "BANCO CETELEM ARGENTINA S.A."],
            ['nombre' => "BANCO DE SERVICIOS FINANCIEROS S.A."],
            ['nombre' => "BANCO BRADESCO ARGENTINA S.A.U."],
            ['nombre' => "BANCO DE SERVICIOS Y TRANSACCIONES S.A."],
            ['nombre' => "RCI BANQUE S.A."],
            ['nombre' => "BACS BANCO DE CREDITO Y SECURITIZACION S"],
            ['nombre' => "BANCO MASVENTAS S.A."],
            ['nombre' => "WILOBANK S.A.U."],
            ['nombre' => "NUEVO BANCO DE ENTRE RÍOS S.A."],
            ['nombre' => "BANCO COLUMBIA S.A."],
            ['nombre' => "BANCO BICA S.A."],
            ['nombre' => "BANCO COINAG S.A."],
            ['nombre' => "BANCO DE COMERCIO S.A."],
            ['nombre' => "BANCO SUCREDITO REGIONAL S.A.U."],
            ['nombre' => "BANCO DINO S.A."],
            ['nombre' => "BANK OF CHINA LIMITED SUCURSAL BUENOS AI"],
            ['nombre' => "FORD CREDIT COMPAÑIA FINANCIERA S.A."],
            ['nombre' => "COMPAÑIA FINANCIERA ARGENTINA S.A."],
            ['nombre' => "VOLKSWAGEN FINANCIAL SERVICES COMPAÑIA F"],
            ['nombre' => "IUDU COMPAÑÍA FINANCIERA S.A."],
            ['nombre' => "FCA COMPAÑIA FINANCIERA S.A."],
            ['nombre' => "GPAT COMPAÑIA FINANCIERA S.A.U."],
            ['nombre' => "MERCEDES-BENZ COMPAÑÍA FINANCIERA ARGENT"],
            ['nombre' => "ROMBO COMPAÑÍA FINANCIERA S.A."],
            ['nombre' => "JOHN DEERE CREDIT COMPAÑÍA FINANCIERA S."],
            ['nombre' => "PSA FINANCE ARGENTINA COMPAÑÍA FINANCIER"],
            ['nombre' => "TOYOTA COMPAÑÍA FINANCIERA DE ARGENTINA"],
            ['nombre' => "NARANJA DIGITAL COMPAÑÍA FINANCIERA S.A."],
            ['nombre' => "MONTEMAR COMPAÑIA FINANCIERA S.A."],
            ['nombre' => "TRANSATLANTICA COMPAÑIA FINANCIERA S.A."],
            ['nombre' => "CREDITO REGIONAL COMPAÑIA FINANCIERA S.A"],
            ['nombre' => "BANCO ITAU ARGENTINA S.A."],
        ];

        DB::table('bancos')->insert($bancos);
    }
}
