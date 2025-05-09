<?php
const BASE_URL = "http://localhost:8080/recursos_humanos/cicsafleet";
// CAMBIAR URL TAMBIEN PARA RESPALDOS DE DB
//Zona horaria
date_default_timezone_set('America/Guatemala');

//Datos de conexión a Base de Datos
const DB_HOST = "localhost";
const DB_NAME = "recursos_db";
const DB_USER = "user_carso";
const DB_PASSWORD = "pA0Hx436zY7x";
const DB_CHARSET = "utf8";

const ENVIRONMENT = 0; // Local: 0, Produccón: 1;

//Módulos
const DASHBOARD = 1;
const PERSONAL = 2;
const INFO  = 3;
const UNIFORMES = 4;
const EXPEDIENTES = 5;
const ACADEMICA = 6;
const USUARIOS = 7;
const ROLES = 8;
const RECLUTAMIENTO = 9;
const BAJAS = 10; 
const ESPERA = 11;
const RECHAZOS = 12;
const LISTADO = 13;
const VACACIONES = 14;
const ADMINISTRATIVO = 15;
const OPERATIVA = 16;
const PERIODOS = 17;
const HISTORIAL = 18;
const MOVIMIENTOS = 19;

const NOMBRE_EMPESA = "Nacel de CentroAmerica";
const TIPO_DOCUMENTO = "DPI";
const NOMBRE_REMITENTE = "SISTEMA DE GESTIÓN DE PERSONAL";
const NOMBRE_SISTEMA = "SISTEMA DE GESTIÓN DE PERSONAL";
const WEB_EMPRESA = "http://www.carsoca.com";
