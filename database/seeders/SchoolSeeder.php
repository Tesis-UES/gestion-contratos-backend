<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\{SchoolAuthority,CentralAuthority};
class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*INGENIERIA */
        School::create(['faculty_id'=>'1', 'name' =>'Arquitectura']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>1]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>1]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería Civil']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>2]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>2]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería Industrial']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>3]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>3]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería Mecánica']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>4]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>4]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería Eléctrica']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>5]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>5]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería Química']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>6]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>6]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería de Alimentos']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>7]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>7]);
        School::create(['faculty_id'=>'1', 'name' =>'Ingeniería en Sistemas Informáticos']);
        SchoolAuthority::create(['name'=>'Ing. Rudy Wilfredo Chicas','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>8]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>8]);
        School::create(['faculty_id'=>'1', 'name' =>'Unidad de Ciencias Basicas']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>9]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>9]);
        School::create(['faculty_id'=>'1', 'name' =>'Maestría en Gestión de Recursos Hidrológicos']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>10]);
        SchoolAuthority::create(['name'=>'PRUEBA  SECRETARIO','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>10]);
        
        /*AGRONOMIA
        School::create(['faculty_id'=>'2', 'name' =>'Ingeniería Agronómica']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>11]);
        School::create(['faculty_id'=>'2', 'name' =>'Licenciatura en Medicina Veterinaria y Zootecnia']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>12]);
        School::create(['faculty_id'=>'2', 'name' =>'Maestría en Gestión Integral del Agua']); 
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>13]);
        
        NATRUALES
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Geofísica']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Biología']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Física']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Matemática']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Estadística']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Ciencias Químicas']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Física']);
        School::create(['faculty_id'=>'3', 'name' =>'Licenciatura en Biología']);
        School::create(['faculty_id'=>'3', 'name' =>'Maestría en Didáctica de la Matemática']);
        School::create(['faculty_id'=>'3', 'name' =>'Maestría en Química']);
        School::create(['faculty_id'=>'3', 'name' =>'Maestría en Física']);
        School::create(['faculty_id'=>'3', 'name' =>'Maestría en Gestión Ambiental']);
        School::create(['faculty_id'=>'3', 'name' =>'Manejo Sustentable de los Recursos Naturales Continentales']);
        School::create(['faculty_id'=>'3', 'name' =>'Profesorado en Ciencias Naturales (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'3', 'name' =>'Profesorado en Educación Media (enseñanza de la biología)']);
        School::create(['faculty_id'=>'3', 'name' =>'Profesorado en Matemática (tercer ciclo y bachillerato)']);
        
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'14']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'15']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'16']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'17']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'18']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'19']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'20']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'21']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'22']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'23']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'24']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'25']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'26']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'27']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'28']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'29']);

        CIENCIAS ECONOMICAS 
        School::create(['faculty_id'=>'4', 'name' =>'Licenciatura en Administración de Empresas']);
        School::create(['faculty_id'=>'4', 'name' =>'Licenciatura en Contaduría Pública']);
        School::create(['faculty_id'=>'4', 'name' =>'Licenciatura en Economía']);
        School::create(['faculty_id'=>'4', 'name' =>'Licenciatura en Mercadeo Internacional']);
        School::create(['faculty_id'=>'4', 'name' =>'Maestría en Administración de Empresas']);
        School::create(['faculty_id'=>'4', 'name' =>'Maestría en Administración Financiera']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'30']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'31']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'32']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'33']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'34']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'35']);


        ODONTO <3 
        School::create(['faculty_id'=>'5', 'name' =>'Doctorado en Cirugía Dental']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'36']);

        QUIMICA 
        School::create(['faculty_id'=>'6', 'name' =>'Licenciatura en Química y Farmacia']);
        School::create(['faculty_id'=>'7', 'name' =>'Licenciatura en Ciencias Jurídicas']);
        School::create(['faculty_id'=>'7', 'name' =>'Licenciatura en Relaciones Internacionales']);
        School::create(['faculty_id'=>'7', 'name' =>'Maestría en Relaciones Internacionales']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'37']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'38']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'39']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'40']);


        MEDICINA
        School::create(['faculty_id'=>'8', 'name' =>'Doctorado en Medicina']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Laboratorio Clínico']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Anestesiología e Inhaloterapia']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Radiología e Imágenes']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Nutrición']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Educación para la Salud']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Ecotecnología']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Salud Materno Infantil']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Fisioterapia y Terapia Ocupacional']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Salud Ambiental']);
        School::create(['faculty_id'=>'8', 'name' =>'Licenciatura en Enfermería']);
        School::create(['faculty_id'=>'8', 'name' =>'Maestría en Salud Pública']);
        School::create(['faculty_id'=>'8', 'name' =>'Maestría en Servicios Integrales de Salud Sexual y Reproductiva']);
        School::create(['faculty_id'=>'8', 'name' =>'Maestría en Educación, en Salud Sexual y Reproductiva']);
        School::create(['faculty_id'=>'8', 'name' =>'Tecnología en Anestesiología']);
        School::create(['faculty_id'=>'8', 'name' =>'Tecnología en Radiología']);
        School::create(['faculty_id'=>'8', 'name' =>'Tecnología en Fisioterapia']);
        School::create(['faculty_id'=>'8', 'name' =>'Técnico en Laboratorio Clínico']);
        School::create(['faculty_id'=>'9', 'name' =>'Arquitectura']);
        School::create(['faculty_id'=>'9', 'name' =>'Curso de Formación Pedagógica para Profesionales']);
        School::create(['faculty_id'=>'9', 'name' =>'Doctorado en Medicina']);
        School::create(['faculty_id'=>'9', 'name' =>'Ingeniería Agronómica']);
        School::create(['faculty_id'=>'9', 'name' =>'Ingeniería Civil']);
        School::create(['faculty_id'=>'9', 'name' =>'Ingeniería Industrial']);
        School::create(['faculty_id'=>'9', 'name' =>'Ingeniería Mecánica']);
        School::create(['faculty_id'=>'9', 'name' =>'Ingeniería Eléctrica']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Laboratorio Clínico']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Anestesiología e Inhaloterapia']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Fisioterapia y Terapia Ocupacional']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Ciencias Jurídicas']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Educación (educación básica)']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Sociología']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Psicología']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Letras']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Educación']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Química y Farmacia']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Economía']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Contaduría Pública']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Administración de Empresas']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Matemática']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Estadística']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Ciencias Químicas']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Física']);
        School::create(['faculty_id'=>'9', 'name' =>'Licenciatura en Biología']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Educación Parvularia']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Educación Básica (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Lenguaje y Literatura (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Idioma Inglés (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Ciencias Sociales (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Ciencias Naturales (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'9', 'name' =>'Profesorado en Matemática (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'10', 'name' =>'Arquitectura']);
        School::create(['faculty_id'=>'10', 'name' =>'Doctorado en Medicina']);
        School::create(['faculty_id'=>'10', 'name' =>'Curso de Formación Pedagógica para Profesionales']);
        School::create(['faculty_id'=>'10', 'name' =>'Ingeniería Civil']);
        School::create(['faculty_id'=>'10', 'name' =>'Ingeniería Industrial']);
        School::create(['faculty_id'=>'10', 'name' =>'Ingeniería Mecánica']);
        School::create(['faculty_id'=>'10', 'name' =>'Ingeniería Eléctrica']);
        School::create(['faculty_id'=>'10', 'name' =>'Ingeniería Química']);
        School::create(['faculty_id'=>'10', 'name' =>'Ingeniería de Sistemas Informáticas']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Ciencias Jurídicas']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Sociología']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Psicología']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Letras']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Idioma Inglés (enseñanza)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (inglés)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Ciencias del Lenguaje y la Literatura']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (ciencias naturales)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (matemética)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (servicio alternativo)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (lenguaje y literatura)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Educación (ciencias sociales)']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Química y Farmacia']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Contaduría Pública']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Administración de Empresas']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Mercadeo Internacional']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Biología']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Estadística']);
        School::create(['faculty_id'=>'10', 'name' =>'Licenciatura en Ciencias Químicas']);
        School::create(['faculty_id'=>'10', 'name' =>'Maestrái en Profesionalización de la Docencia Superior']);
        School::create(['faculty_id'=>'10', 'name' =>'Maestría en Métodos y Técnicas de Investigación Social']);
        School::create(['faculty_id'=>'10', 'name' =>'Maestría en Administración Financiera']);
        School::create(['faculty_id'=>'10', 'name' =>'Profesorado en Educación Básica (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'10', 'name' =>'Profesorado en Lenguaje y Literatura (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'10', 'name' =>'Profesorado en Idioma Inglés (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'10', 'name' =>'Profesorado en Ciencias Sociales (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'10', 'name' =>'Profesorado en Ciencias Naturales (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'10', 'name' =>'Profesorado en Matemática (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'11', 'name' =>'Ingeniería Agronómica']);
        School::create(['faculty_id'=>'11', 'name' =>'Ingeniería de Sistemas Informáticos']);
        School::create(['faculty_id'=>'11', 'name' =>'Licenciatura en Educación (ciencias sociales)']);
        School::create(['faculty_id'=>'11', 'name' =>'Licenciatura en Contaduría Pública']);
        School::create(['faculty_id'=>'11', 'name' =>'Profesorado en Educación Parvularia']);
        School::create(['faculty_id'=>'11', 'name' =>'Profesorado en Educación Básica (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'11', 'name' =>'Profesorado en Ciencias Sociales (tercer ciclo)']);
        School::create(['faculty_id'=>'11', 'name' =>'Profesorado en Ciencias Naturales (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'11', 'name' =>'Profesorado en Matemática (tercer ciclo y bachillerato)']);
        School::create(['faculty_id'=>'11', 'name' =>'Profesorado en Educación Media (biología)']);
        School::create(['faculty_id'=>'12', 'name' =>'Curso de Formación Pedagógica para Profesionales']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Filosofía']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Filosofía']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Sociología']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Psicología']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Letras']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Periodismo']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Idioma Inglés']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Idioma Inglés (enseñanza)']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Lenguas Modernas (francés e inglés)']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Educación (servicio alternativo)']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Educación']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Educación (educación f ísica)']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Artes Plásticas']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Historia']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Trabajo Social']);
        School::create(['faculty_id'=>'12', 'name' =>'Licenciatura en Antropología Sociocultural']);
        School::create(['faculty_id'=>'12', 'name' =>'Maestria en Derechos Humanos y Educación para la Paz']);
        School::create(['faculty_id'=>'12', 'name' =>'Maestria en Métodos y Técnicas de Investigación Social']);
        School::create(['faculty_id'=>'12', 'name' =>'Maestría en Traducción (inglés-español / español-inglés)']);
        School::create(['faculty_id'=>'12', 'name' =>'Maestría en Didáctica y Formación del Profesorado']);
        School::create(['faculty_id'=>'12', 'name' =>'Maestría en Didáctica para la Formación Docente']);
        School::create(['faculty_id'=>'12', 'name' =>'Maestria en Formación para la Docencia Universitaria']);
        School::create(['faculty_id'=>'12', 'name' =>'Profesorado en Educación Parvularia']);
        School::create(['faculty_id'=>'12', 'name' =>'Profesorado en Educación Básica (primero y segundo ciclos)']);
        School::create(['faculty_id'=>'12', 'name' =>'Profesorado en Educación Media (enseñanza del inglés)']);
        School::create(['faculty_id'=>'12', 'name' =>'Profesorado en Lenguaje y Literatura (tercer c iclo)']);
        School::create(['faculty_id'=>'12', 'name' =>'Profesorado en Idioma Inglés (tercer ciclo)']);
        School::create(['faculty_id'=>'12', 'name' =>'Profesorado en Ciencias Sociales (tercer ciclo)']);
        School::create(['faculty_id'=>'12', 'name' =>'Técnico en Bibliotecología']);
        SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'41']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'42']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'43']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'44']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'45']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'46']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'47']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'48']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'49']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'50']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'51']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'52']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'53']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'54']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'55']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'56']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'57']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'58']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'59']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'60']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'61']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'62']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'63']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'64']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'65']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'66']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'67']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'68']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'69']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'70']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'71']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'72']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'73']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'74']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'75']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'76']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'77']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'78']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'79']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'80']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'81']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'82']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'83']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'84']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'85']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'86']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'87']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'88']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'89']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'90']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'91']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'92']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'93']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'94']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'95']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'96']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'97']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'98']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'99']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'100']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'101']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'102']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'103']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'104']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'105']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'106']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'107']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'108']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'109']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'110']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'111']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'112']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'113']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'114']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'115']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'116']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'117']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'118']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'119']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'120']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'121']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'122']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'123']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'124']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'125']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'126']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'127']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'128']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'129']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'130']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'131']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'132']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'133']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'134']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'135']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'136']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'137']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'138']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'139']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'140']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'141']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'142']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'143']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'144']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'145']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'146']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'147']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'148']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'149']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'150']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'151']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'152']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'153']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'154']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'155']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'156']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'157']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'158']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'159']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'160']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'161']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'162']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'163']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'164']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'165']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'166']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'167']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'168']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'169']);
SchoolAuthority::create(['name'=>'PRUEBA  DIRECTOR','position'=>'DIRECTOR','startPeriod'=>'2021-02-02','endPeriod'=>'2021-12-02','school_id'=>'170']);
*/

        CentralAuthority::create([
            'position'      =>'Rector',
            'firstName'     =>'ROGER',
            'middleName'    =>'ARMANDO',
            'lastName'      =>'ARIAS ALVARADO',
            'dui'           =>'01645197-6',
            'nit'           =>'0403-200868-101-7',
            'start_period'  =>'2021-02-02',
            'end_period'    =>'2021-12-02',
            'text_dui'      =>'cero un millon seiscientos cuarenta y cinco mil ciento noventa y siete guion seis',
            'text_nit'      =>'cero cuatrocientos tres - doscientos mil ochocientos sesenta y ocho - ciento uno-siete',
            'birth_date'    =>'1968-08-20',
            'profession'    =>'Economista',
            'reading_signature'=>'R. Arias'
        ]);
    }
}
