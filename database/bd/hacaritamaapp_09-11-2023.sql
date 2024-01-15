-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-11-2023 a las 10:57:34
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hacaritamaapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencia`
--

CREATE TABLE `agencia` (
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla agencia',
  `persidresponsable` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `agendepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `agenmuniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio',
  `agennombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del la agencia',
  `agendireccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dirección de la agencia',
  `agencorreo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo de la agencia',
  `agentelefonocelular` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono celular de la agencia',
  `agentelefonofijo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono fijo de la agencia',
  `agenactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la agencia se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `agencia`
--

INSERT INTO `agencia` (`agenid`, `persidresponsable`, `agendepaid`, `agenmuniid`, `agennombre`, `agendireccion`, `agencorreo`, `agentelefonocelular`, `agentelefonofijo`, `agenactiva`, `created_at`, `updated_at`) VALUES
(1, 2, 18, 804, 'PRINCIPAL', 'Calle 7 a 56 211 la ondina vía a rio de oro', 'cootranshacaritama@hotmail.com', '3146034311', '5611012', 1, '2023-11-09 09:30:39', '2023-11-09 09:30:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivohistorico`
--

CREATE TABLE `archivohistorico` (
  `archisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla archivo histórico',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tipo documento',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el registro del documento',
  `tiesarid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo estante archivador',
  `ticaubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de caja ubicacion',
  `ticrubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de carpeta ubicacion',
  `archisfechahora` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se crea el registro del documento',
  `archisfechadocumento` date DEFAULT NULL COMMENT 'Fecha que contiene el documento',
  `archisnumerofolio` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de folio que posee el documento del archivo histórico',
  `archisasuntodocumento` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto que posee el documento del archivo histórico',
  `archistomodocumento` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tomo que posee el documento del archivo histórico',
  `archiscodigodocumental` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código que posee el documento del archivo histórico',
  `archisentidadremitente` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Entidad remitente que posee el documento del archivo histórico',
  `archisentidadproductora` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Entidad productora que posee el documento del archivo histórico',
  `archisresumendocumento` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'REsumen que posee el documento del archivo histórico',
  `archisobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación general del registro del archivo histórico',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivohistoricodigitalizado`
--

CREATE TABLE `archivohistoricodigitalizado` (
  `arhidiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `archisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador del archivo histórico',
  `arhidinombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el archivo digitalizado',
  `arhidinombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el archivo digitalizado pero editado',
  `arhidirutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta enfuscada del archivo digitalizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociado`
--

CREATE TABLE `asociado` (
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla asociado',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tiesasid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado del asociado',
  `asocfechaingreso` date NOT NULL COMMENT 'Fecha de ingreso del asocado a la cooperativa',
  `asocfecharetiro` date DEFAULT NULL COMMENT 'Fecha de retiro del asocado a la cooperativa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociadocambioestado`
--

CREATE TABLE `asociadocambioestado` (
  `ascaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla asociado cambio estado',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `tiesasid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado asociado',
  `ascaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del asociado',
  `ascaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del asociado',
  `ascaesobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado del asociado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociadovehiculo`
--

CREATE TABLE `asociadovehiculo` (
  `asovehid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla asociado vehículo',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargolaboral`
--

CREATE TABLE `cargolaboral` (
  `carlabid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla cargo laboral',
  `carlabnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del cargo laboral',
  `carlabactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el cargo laboral',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargolaboral`
--

INSERT INTO `cargolaboral` (`carlabid`, `carlabnombre`, `carlabactivo`, `created_at`, `updated_at`) VALUES
(1, 'Desarrollador', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(2, 'Asociado', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(3, 'Conductor', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(4, 'Gerente', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(5, 'Jefe de área', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(6, 'Secretaria', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoacta`
--

CREATE TABLE `coddocumprocesoacta` (
  `codopaid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso acta',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de acta',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopaconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo del acta',
  `codopasigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora del acta',
  `codopaanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea el acta',
  `codopahorainicio` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hora de inicio del acta',
  `codopahorafinal` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hora de final del acta',
  `codopalugar` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lugar donde se realiza el acta',
  `codopaquorum` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Quorum reglamentario para el acta',
  `codopaordendeldia` varchar(4000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Orden del dñia del acta',
  `codopainvitado` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Personas invitados para el acta',
  `codopaausente` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Persona usente en la generación para el acta',
  `codopaconvocatoria` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el acta tiene conovocatoria',
  `codopaconvocatorialugar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lugar conovocatoria para el acta',
  `codopaconvocatoriafecha` date DEFAULT NULL COMMENT 'Fecha para la conovocatoria del acta',
  `codopaconvocatoriahora` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hora de la conovocatoria del acta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoanexo`
--

CREATE TABLE `coddocumprocesoanexo` (
  `codopxid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso anexo',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `codopxnombreanexooriginal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento',
  `codopxnombreanexoeditado` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento pero editado',
  `codopxrutaanexo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta enfuscada del anexo para el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocambioestado`
--

CREATE TABLE `coddocumprocesocambioestado` (
  `codpceid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso cambio estado',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `codpceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del documento',
  `codpcefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del documento',
  `codpceobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocertificado`
--

CREATE TABLE `coddocumprocesocertificado` (
  `codopcid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso certificado',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de persona documental',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopcconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la certificado',
  `codopcsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la certificado',
  `codopcanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la certificado',
  `codopctitulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título con el que se crea la certificado',
  `codopccontenidoinicial` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'contenido incial de la certificado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocircular`
--

CREATE TABLE `coddocumprocesocircular` (
  `codoplid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso circular',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `codoplconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la circular',
  `codoplsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la circular',
  `codoplanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la circular',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocitacion`
--

CREATE TABLE `coddocumprocesocitacion` (
  `codoptid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso citación',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de acta',
  `codoptconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo del citación',
  `codoptsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora del citación',
  `codoptanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea el citación',
  `codopthora` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hora de la citación',
  `codoptlugar` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lugar donde se realiza el citación',
  `codoptfecharealizacion` date NOT NULL COMMENT 'Fecha para la conovocatoria del citación',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocompartido`
--

CREATE TABLE `coddocumprocesocompartido` (
  `codopdid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso compartido',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario al que se le comparte el documento',
  `codopdfechacompartido` datetime NOT NULL COMMENT 'Fecha y hora en la cual se comparte el documento',
  `codopdfechaleido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se lee el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoconstancia`
--

CREATE TABLE `coddocumprocesoconstancia` (
  `codopnid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso constancia',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de persona documental',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopnconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la constancia',
  `codopnsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la constancia',
  `codopnanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la constancia',
  `codopntitulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título con el que se crea la constancia',
  `codopncontenidoinicial` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'contenido incial de la constancia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocopia`
--

CREATE TABLE `coddocumprocesocopia` (
  `codoppid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso copia',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `depeid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la dependencia',
  `codoppescopiadocumento` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si es una copia en el documento',
  `codoppfechacompartido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se comparte el documento',
  `codoppfechaleido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se lee el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesofirma`
--

CREATE TABLE `coddocumprocesofirma` (
  `codopfid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso firma',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona',
  `carlabid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la tabla cargo laboral',
  `codopftoken` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Token con el cual es firmado el documento',
  `codopffechahorafirmado` datetime DEFAULT NULL COMMENT 'Fecha y hora de la cual se firma el documento',
  `codopffechahoranotificacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de la cual se envio la notifiación del token',
  `codopffechahoramaxvalidez` datetime DEFAULT NULL COMMENT 'Fecha y hora maxima de validez del token',
  `codopfmensajecorreo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al correo',
  `codopfmensajecelular` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al celular',
  `codopffirmado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento esta firmado',
  `codopfesinvitado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el que firma es invitado en el acta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesooficio`
--

CREATE TABLE `coddocumprocesooficio` (
  `codopoid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso oficio',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `tipsalid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo saludo',
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `codopoconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la oficio',
  `codoposigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la oficio',
  `codopoanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la oficio',
  `codopotitulo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Título de la persona a la que va dirigido el ofico',
  `codopociudad` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ciudad a la que va dirigido el oficio',
  `codopocargodestinatario` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del cargo de la persona ala que va dirigido el oficio',
  `codopoempresa` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona o empresa a la que va dirigido el oficio',
  `codopodireccion` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'direción de la persona o empresa a la que va dirigido el oficio',
  `codopotelefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Telefono de la persona o empresa a la que va dirigido el oficio',
  `codoporesponderadicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si se esta respondiendo radicados en el oficio',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoraddocentrante`
--

CREATE TABLE `coddocumprocesoraddocentrante` (
  `cdprdeid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso radicado documento entrante',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigodocumental`
--

CREATE TABLE `codigodocumental` (
  `coddocid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental',
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la serie documental',
  `susedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la sub serie',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tipo documento',
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de medio',
  `tiptraid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de trámite',
  `tipdetid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de destino',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `coddocfechahora` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se crea el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigodocumentalproceso`
--

CREATE TABLE `codigodocumentalproceso` (
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `coddocid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental',
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `codoprfecha` date NOT NULL COMMENT 'Fecha en la cual se crea el documento',
  `codoprnombredirigido` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre o nombres de la persona a quien va dirigido el documento',
  `codoprcargonombredirigido` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cargo de la persona a quien va dirigido el documento',
  `codoprasunto` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Asunto por el cual se crea el documento o título de la resolución',
  `codoprcorreo` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo de la persona o personas a quien van dirigir el documento',
  `codoprcontenido` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contenido del documento',
  `codoprtieneanexo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento tiene anexo',
  `codopranexonombre` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del adjunto que se relaciona en el documento',
  `codoprtienecopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento tiene copia',
  `codoprcopianombre` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona a quien va dirigido el documento como copia',
  `codoprrutadocumento` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la ruta al sellar el documento',
  `codoprsolicitafirma` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento se le ha solicitado la firma',
  `codoprfirmado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento ha sido firmado',
  `codoprsellado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento esta sellado',
  `codoprradicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento fue radicado en ventanilla única',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colocacion`
--

CREATE TABLE `colocacion` (
  `coloid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla solicitud de credito desembolso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea la colocación',
  `solcreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la solicitud de crédito',
  `tiesclid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo estado solicitud colocación',
  `colofechahoraregistro` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se registra la colocacion',
  `colofechadesembolso` date NOT NULL COMMENT 'Fecha de desembolso del crédito',
  `coloanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se desembolsa el crédito',
  `colonumerodesembolso` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de desembolso asignado por cada año',
  `colovalordesembolsado` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Monto o valor desembolsado',
  `colotasa` decimal(6,2) NOT NULL COMMENT 'Tasa de interés aplicado en el desembolso',
  `colonumerocuota` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de cuota aprobado en el desembolso',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colocacioncambioestado`
--

CREATE TABLE `colocacioncambioestado` (
  `cocaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla colocación cambio estado',
  `coloid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la colocación',
  `tiesclid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado colocación',
  `cocaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado de la colocación',
  `cocaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado de la colocación',
  `cocaesobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado de la colocación',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colocacionliquidacion`
--

CREATE TABLE `colocacionliquidacion` (
  `colliqid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla colocación liquidación',
  `coloid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la solicitud de crédito',
  `colliqnumerocuota` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de cuota de la colocación',
  `colliqvalorcuota` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Monto o valor de la cuota de la colocación',
  `colliqfechavencimiento` date NOT NULL COMMENT 'Fecha de vencimiento de la cuota de la colocación',
  `colliqnumerocomprobante` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de comprobante de pago de la cuota de la colocación',
  `colliqfechapago` date DEFAULT NULL COMMENT 'Fecha de pago de la cuota de la colocación',
  `colliqvalorpagado` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Valor pagado en la cuota de la colocación',
  `colliqsaldocapital` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Saldo a capital de la colocación',
  `colliqvalorcapitalpagado` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Valor capital pagado la colocación',
  `colliqvalorinterespagado` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Valor interés pagado la colocación',
  `colliqvalorinteresmora` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Valor interés de mora pagado la colocación',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductor`
--

CREATE TABLE `conductor` (
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tiescoid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado del conductor',
  `tipconid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de conductor',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia a la que esta asignado el vehículo',
  `condfechaingreso` date NOT NULL COMMENT 'Fecha de ingreso del conductor a la cooperativa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductorcambioestado`
--

CREATE TABLE `conductorcambioestado` (
  `cocaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor cambio estado',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `tiescoid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado conductor',
  `cocaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del conductor',
  `cocaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del conductor',
  `cocaesobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado del conductor',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductorlicencia`
--

CREATE TABLE `conductorlicencia` (
  `conlicid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor licencia',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `ticaliid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de categoría de la licencia',
  `conlicnumero` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número del licencia',
  `conlicfechaexpedicion` date NOT NULL COMMENT 'Fecha de expedición de la licencia',
  `conlicfechavencimiento` date NOT NULL COMMENT 'Fecha de vencimiento de la licencia',
  `conlicextension` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Extensión del archivo que se anexa a la licencia',
  `conlicnombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa a la licencia',
  `conlicnombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa a la licencia',
  `conlicrutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa a la licencia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductorvehiculo`
--

CREATE TABLE `conductorvehiculo` (
  `convehid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor vehículo',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `depaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla departamento',
  `depacodigo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Codigo del departamento',
  `depanombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del departamento',
  `depahacepresencia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la entidad hace presencia en este departamento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`depaid`, `depacodigo`, `depanombre`, `depahacepresencia`, `created_at`, `updated_at`) VALUES
(1, '05', 'Antioquia', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(2, '08', 'Atlántico', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(3, '11', 'Bogotá', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(4, '13', 'Bolivar', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(5, '15', 'Boyaca', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(6, '17', 'Caldas', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(7, '18', 'Caquetá', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(8, '19', 'Cauca', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(9, '20', 'Cesar', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(10, '23', 'Cordoba', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(11, '25', 'Cundinamarca', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(12, '27', 'Chocó', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(13, '41', 'Huila', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(14, '44', 'La Guajira', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(15, '47', 'Magdalena', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(16, '50', 'Meta', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(17, '52', 'Nariño', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(18, '54', 'Norte de Santander', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(19, '63', 'Quindio', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(20, '66', 'Risaralda', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(21, '68', 'Santander', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(22, '70', 'Sucre', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(23, '73', 'Tolima', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(24, '76', 'Valle del Cauca', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(25, '81', 'Arauca', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(26, '85', 'Casanare', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(27, '86', 'Putumayo', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(28, '88', 'San Andrés', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(29, '91', 'Amazonas', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(30, '94', 'Guainia', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(31, '95', 'Guaviare', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(32, '97', 'Vaupes', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(33, '99', 'Vichada', 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia',
  `depejefeid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del jefe de la dependencia',
  `depecodigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de la dependencia',
  `depesigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia',
  `depenombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la dependencia',
  `depecorreo` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo de la dependencia',
  `depeactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la dependencia se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependencia`
--

INSERT INTO `dependencia` (`depeid`, `depejefeid`, `depecodigo`, `depesigla`, `depenombre`, `depecorreo`, `depeactiva`, `created_at`, `updated_at`) VALUES
(1, 1, '100', 'GER', 'GERENCIA', 'rdsalazarr@ufpso.edu.co', 1, '2023-11-09 09:31:49', '2023-11-09 09:31:49'),
(2, 1, '200', 'CON', 'CONTABILIDAD', 'radasa10@hotmail.com', 1, '2023-11-09 09:31:49', '2023-11-09 09:31:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependenciapersona`
--

CREATE TABLE `dependenciapersona` (
  `depperid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia persona',
  `depperdepeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `depperpersid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del persona asignado a la dependencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependenciapersona`
--

INSERT INTO `dependenciapersona` (`depperid`, `depperdepeid`, `depperpersid`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependenciasubseriedocumental`
--

CREATE TABLE `dependenciasubseriedocumental` (
  `desusdid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia sub serie documental',
  `desusdsusedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla sub serie',
  `desusddepeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependenciasubseriedocumental`
--

INSERT INTO `dependenciasubseriedocumental` (`desusdid`, `desusdsusedoid`, `desusddepeid`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `emprid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla empresa',
  `persidrepresentantelegal` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `emprdepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `emprmuniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio',
  `emprnit` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nit de la empresa',
  `emprdigitoverificacion` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dígito de verificación de la empresa',
  `emprnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la empresa',
  `emprsigla` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sigla de la empresa',
  `emprlema` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lema de la empresa',
  `emprdireccion` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dirección de la empresa',
  `emprbarrio` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Barrio de la empresa',
  `emprpersoneriajuridica` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Personería jurídica de la empresa',
  `emprcorreo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo de la empresa',
  `emprtelefonofijo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono fijo de contacto con la empresa',
  `emprtelefonocelular` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono celular de contacto con la empresa',
  `emprhorarioatencion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Horario de atención',
  `emprurl` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Url de la página web institucional',
  `emprcodigopostal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código postal',
  `emprlogo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Logo de la empresa en en formato png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`emprid`, `persidrepresentantelegal`, `emprdepaid`, `emprmuniid`, `emprnit`, `emprdigitoverificacion`, `emprnombre`, `emprsigla`, `emprlema`, `emprdireccion`, `emprbarrio`, `emprpersoneriajuridica`, `emprcorreo`, `emprtelefonofijo`, `emprtelefonocelular`, `emprhorarioatencion`, `emprurl`, `emprcodigopostal`, `emprlogo`, `created_at`, `updated_at`) VALUES
(1, 2, 18, 804, '890.505.424', '7', 'COOPERATIVA DE TRANSPORTADORES HACARITAMA', 'COOTRANSHACARITAMA', 'La empresa que integra la region', 'Calle 7 a 56 211 la ondina vía a rio de oro', 'Santa Clara', 'Personería Jurídica No. 73 enero 28 de 1976', 'cootranshacaritama@hotmail.com', '5611012', '3146034311', 'Lunes a Viernes De 8:00 a.m a 12:00  y de 2:00 p.m a 6:00 p.m', 'www.cootranshacaritama.com', '546552', '890505424_logoHacaritama.png', '2023-11-09 09:31:22', '2023-11-09 09:31:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `festivo`
--

CREATE TABLE `festivo` (
  `festid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla festivo',
  `festfecha` date NOT NULL COMMENT 'Fecha que corresponde a un festivo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionalidad`
--

CREATE TABLE `funcionalidad` (
  `funcid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla funcionalidad',
  `moduid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del módulo',
  `funcnombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la funcionalidad',
  `functitulo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Título de la funcionalidad',
  `funcruta` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la funcionalidad',
  `funcicono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clase de css para montar en el link del menú',
  `funcorden` smallint(6) NOT NULL COMMENT 'Orden del en el árbol del menú',
  `funcactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la funcionalidad encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `funcionalidad`
--

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 'Menú', 'Gestionar menú', 'admin/configurar/menu', 'add_chart', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(2, 1, 'Notificación correo', 'Gestionar información de notificar correo', 'admin/configurar/notificarCorreo', 'mail_outline_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(3, 1, 'Información PDF', 'Gestionar información PDF', 'admin/configurar/GeneralPdf', 'picture_as_pdf', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(4, 1, 'Datos territorial', 'Gestionar datos territorial', 'admin/configurar/datosTerritorial', 'language_icon', 4, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(5, 1, 'Empresa', 'Gestionar empresa', 'admin/configurar/empresa', 'store', 5, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(6, 2, 'Tipos', 'Gestionar tipos', 'admin/gestionar/tipos', 'star_rate_icon', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(7, 2, 'Series', 'Gestionar series documentales', 'admin/gestionar/seriesDocumentales', 'insert_chart_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(8, 2, 'Dependencia', 'Gestionar dependencia', 'admin/gestionar/dependencia', 'maps_home_work_icon', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(9, 2, 'Persona', 'Gestionar persona', 'admin/gestionar/persona', 'person_icon', 4, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(10, 2, 'Usuario', 'Gestionar usuario', 'admin/gestionar/usuario', 'person', 5, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(12, 2, 'Festivos', 'Gestionar festivos', 'admin/gestionar/festivos', 'calendar_month_icon', 6, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(13, 2, 'Agencia', 'Gestionar agencia', 'admin/gestionar/agencia', 'holiday_village_con', 7, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(14, 3, 'Acta', 'Gestionar actas', 'admin/produccion/documental/acta', 'local_library_icon', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(15, 3, 'Certificado', 'Gestionar certificados', 'admin/produccion/documental/certificado', 'note_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(16, 3, 'Circular', 'Gestionar circulares', 'admin/produccion/documental/circular', 'menu_book_icon', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(17, 3, 'Citación', 'Gestionar citaciones', 'admin/produccion/documental/citacion', 'collections_bookmark_icon', 4, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(18, 3, 'Constancia', 'Gestionar constancias', 'admin/produccion/documental/constancia', 'import_contacts_icon', 5, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(19, 3, 'Oficio', 'Gestionar oficios', 'admin/produccion/documental/oficio', 'library_books_icon', 6, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(20, 3, 'Firmar', 'Firmar documentos', 'admin/produccion/documental/firmar', 'import_contacts_icon', 7, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(21, 4, 'Documento entrante', 'Gestionar documento entrante', 'admin/radicacion/documento/entrante', 'post_add_icon', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(22, 4, 'Anular radicado', 'Gestionar anulado de radicado', 'admin/radicacion/documento/anular', 'layers_clear_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(23, 4, 'Bandeja de radicado', 'Gestionar bandeja de radicado', 'admin/radicacion/documento/bandeja', 'content_paste_go_icon', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(24, 5, 'Gestionar', 'Gestionar archivo histórico', 'admin/archivo/historico/gestionar', 'ac_unit_icon', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(25, 5, 'Consultar', 'Gestionar consulta del archivo histórico', 'admin/archivo/historico/consultar', 'find_in_page_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(26, 6, 'Procesar', 'Procesar asociados', 'admin/gestionar/asociados', 'person_add_alt1_icon', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(27, 6, 'Desvincular', 'Desvincular asociado', 'admin/gestionar/desvincularAsociado', 'person_remove_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(28, 6, 'Inactivos', 'Gestionar asociados inactivos', 'admin/gestionar/asociadosInactivos', 'person_off_icon', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(29, 7, 'Tipos de vehiculos', 'Gestionar tipos de vehículos', 'admin/direccion/transporte/tipos', 'car_crash_icon', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(30, 7, 'Vehículos', 'Gestionar vehículos', 'admin/direccion/transporte/vehiculos', 'electric_car_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(31, 7, 'Conductores', 'Gestionar conductores', 'admin/direccion/transporte/conductores', 'attach_money_icon', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(32, 7, 'Asignación vehículos', 'Gestionar asignación de vehículos', 'admin/direccion/transporte/asignarVehiculo', 'credit_score_icon', 4, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(33, 8, 'Línea de crédito', 'Gestionar línea de crédito', 'admin/cartera/lineaCredito', 'add_chart', 1, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(34, 8, 'Solicitud', 'Gestionar solicitud de crédito', 'admin/cartera/solicitud', 'add_card_icon', 2, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(35, 8, 'Aprobación', 'Aprobar solicitud de crédito', 'admin/cartera/aprobacion', 'credit_score_icon', 3, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(36, 8, 'Desembolso', 'Getionar desembolso', 'admin/cartera/desembolso', 'attach_money_icon', 4, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(37, 8, 'Historial S.C.', 'Getionar historial de solicitud de crédito', 'admin/cartera/historial', 'auto_stories_icon ', 5, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(38, 8, 'Cobranza', 'Getionar cobranza', 'admin/cartera/cobranza', 'table_chart_icon', 6, 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialcontrasena`
--

CREATE TABLE `historialcontrasena` (
  `hisconid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla historial de contrasena',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `hisconpassword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password del usuario utilizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacionconfiguracioncorreo`
--

CREATE TABLE `informacionconfiguracioncorreo` (
  `incocoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla información configuración del correo',
  `incocohost` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Host para el cual se permite enviar el correo',
  `incocousuario` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Usuario o correo con el cual se va autenticar para enviar los correos en el sistema',
  `incococlave` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Clave del correo para acceder a la plataforma',
  `incococlaveapi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Clave de la api para autenticar y poder enviar el corro',
  `incocopuerto` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Puerto por el cual se envia el correo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacionconfiguracioncorreo`
--

INSERT INTO `informacionconfiguracioncorreo` (`incocoid`, `incocohost`, `incocousuario`, `incococlave`, `incococlaveapi`, `incocopuerto`, `created_at`, `updated_at`) VALUES
(1, 'smtp.gmail.com', 'notificacioncootranshacaritama@gmail.com', 'Notific@2023.', 'grgsmqtlmijxaapj', '587', '2023-11-09 09:30:34', '2023-11-09 09:30:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informaciongeneralpdf`
--

CREATE TABLE `informaciongeneralpdf` (
  `ingpdfid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla información general PDF',
  `ingpdfnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre general para utilizar la consulta de la información en PDF',
  `ingpdftitulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título de la información general del PDF',
  `ingpdfcontenido` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contenido de la información que lleva PDF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informaciongeneralpdf`
--

INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(1, 'contratoVehiculo', 'CONTRATO DE VINCULACIÓN CNT-numeroContrato', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Entre los suscritos a saber <strong>nombreGerente</strong>, mayor de edad, vecino de Oca&ntilde;a, identificado con tpDocumentoGerente documentoGerente de ciudadExpDocumentoGerente, quien obra en calidad de Gerente y representante legal de la Cooperativa de Transportadores Hacaritama, Cootranshacaritama, por una parte y que en adelante se llamar&aacute; LA COOPERATIVA y&nbsp;<strong>nombreAsociado&nbsp;</strong>identificado con tpDocumentoAsociado. documentoAsociado de ciudadExpDocumentoAsociado mayor de edad y vecino de OCA&Ntilde;A, por otra parte, y que en adelante se llamar&aacute; ASOCIADO, han celebrado un contrato de vinculaci&oacute;n para cumplir con el Art&iacute;culo 19 de los Estatuto que rigen a LA COOPERATIVA.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: center;\"><strong>CLAUSULAS</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: left;\"><strong>PRIMERA:</strong> El ASOCIADO presenta el siguiente veh&iacute;culo:</p>\r\n<table style=\"border-collapse: collapse; width: 68.381%; border-width: 1px; height: 189.938px; margin-left: 0px; margin-right: auto;\" border=\"1\"><colgroup><col style=\"width: 47.6286%;\"><col style=\"width: 52.4229%;\"></colgroup>\r\n<tbody>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"background-color: rgb(235, 235, 235); height: 0px; text-align: center;\" colspan=\"2\"><strong>DETALLE DEL VEH&Iacute;CULO</strong></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>PLACA</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">placaVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>N&Uacute;MERO INTERNO</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">numeroInternoVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>CLASE &nbsp;</strong></span></td>\r\n<td style=\"height: 13.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">claseVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>CILINDRAJE</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">cilindrajeVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>TIPO DE CARROCER&Iacute;A</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">carroceriaVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>MODELO</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">modeloVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>MARCA &nbsp; &nbsp;</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">marcaVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>COLOR &nbsp; &nbsp;</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">colorVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 0px;\">\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>CAPACIDAD &nbsp; &nbsp;</strong></span></td>\r\n<td style=\"height: 0px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">capacidadVehiculo</span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>El cual se declara libre de acci&oacute;n legal, pleitos pendientes, embargos judiciales, condiciones resolutorias y en general, cualquier otro gravamen. <strong>SEGUNDA</strong>: La duraci&oacute;n del presente contrato es por el t&eacute;rmino de un (1) a&ntilde;o a partir de su perfeccionamiento, dicho contrato no se prorroga de forma autom&aacute;tica, por lo que no es necesario dar preaviso a las partes con anterioridad a su vencimiento. <strong>TERCERA</strong>: El valor a pagar por la suscripci&oacute;n del presente contrato ser&aacute; lo acordado por los estatutos, acuerdos y reglamentos vigentes. <strong>CUARTA</strong>: La COOPERATIVA se obliga a colocar y mantener el plan de rodamiento que para este tipo de veh&iacute;culo le ha se&ntilde;alado el Ministerio de Transporte y/o autoridad competente. <strong>QUINTA</strong>: Los impuestos del veh&iacute;culo, multas, da&ntilde;os a terceros en caso de accidentes, servicios m&eacute;dicos, farmac&eacute;uticos, quir&uacute;rgicos, hospitalarios y dem&aacute;s que se ocasionen por el veh&iacute;culo, gastos de combustibles, dineros entregados, prestaciones sociales, salarios e indemnizaciones, seguros del conductor, entre otros, ser&aacute;n de cuenta exclusiva del asociado propietario del veh&iacute;culo. <strong>SEXTA</strong>: El ASOCIADO se compromete a cancelar en la planilla (cuota administrativa) el valor por concepto de servicios administrativos m&aacute;s el aporte social de conformidad con los estatutos vigentes de la cooperativa. <strong>S&Eacute;PTIMA</strong>: El ASOCIADO se responsabiliza de todas y cada una de las prestaciones sociales de sus trabajadores, manteniendo indemne a la COOPERATIVA de cualquier demanda, denuncia, queja o reclamo, teniendo en cuenta la relaci&oacute;n laboral es &uacute;nica y exclusiva entre el ASOCIADO y el CONDUCTOR del veh&iacute;culo vinculado. <strong>OCTAVA</strong>: El ASOCIADO ser&aacute; el &uacute;nico responsable, indemnizar&aacute; y mantendr&aacute; a la COOPERATIVA indemne y libre de todo tipo de P&eacute;rdidas causadas a la COOPERATIVA, a los ASOCIADOS, al Personal, a Otros ASOCIADOS y/o a terceras.<span style=\"mso-spacerun: yes;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Personas, que sean imputables a los actos u omisiones del ASOCIADO, sus trabajadores y/o el Personal, o que se produzcan como consecuencia del incumplimiento del Contrato, de un Servicio, de los Permisos y/o de las Leyes Aplicables, pudiendo la COOPERATIVA cobrar, compensar o deducir cualquier P&eacute;rdida contra las sumas adeudadas o que lleguen a adeudarse al ASOCIADO bajo cualquier pago pendiente. <strong>NOVENA</strong>:&nbsp; En particular, y sin que ello implique limitaci&oacute;n alguna de lo previsto en la cl&aacute;usula anterior, el ASOCIADO ser&aacute; responsable y mantendr&aacute; al COOPERATIVA indemne frente a todo tipo de P&eacute;rdidas por: (i) cualquier incumplimiento de las Leyes Aplicables, los Permisos y/o de las obligaciones derivadas de actos administrativos expedidos por las Autoridades Competentes, y/o por cualquier afectaci&oacute;n o da&ntilde;o; (ii) P&eacute;rdidas relacionadas con Impuestos que den lugar a un proceso de fiscalizaci&oacute;n o reclamaci&oacute;n de cualquier tipo por parte de las Autoridades Competentes tributarias nacionales o locales, relacionadas con la ejecuci&oacute;n de este Contrato; (iii) cualquier sanci&oacute;n o condena impuesta por las Autoridades Competentes administrativas o judiciales en relaci&oacute;n con el incumplimiento de las obligaciones laborales y de seguridad social del ASOCIADO y sus trabajadores, as&iacute; como por cualquier reclamaci&oacute;n judicial o administrativa iniciada por el trabajador a cargo del ASOCIADO asignado a la ejecuci&oacute;n del Servicio, o por los causahabientes de dicho Personal. <strong>DECIMA</strong>: Las obligaciones de indemnidad del ASOCIADO frente a la COOPERATIVA estar&aacute;n sujetas a los mismos t&eacute;rminos que aquellos aplicables a la prescripci&oacute;n de las acciones correspondientes seg&uacute;n el tipo de reclamaci&oacute;n de que se trate. No obstante, en el evento en que, con posterioridad al vencimiento del correspondiente t&eacute;rmino de prescripci&oacute;n, la COOPERATIVA sea notificada acerca de reclamaciones por P&eacute;rdidas que hayan sido presentadas con anterioridad a dicho vencimiento por terceras Personas (incluyendo el Personal y las Autoridades Competentes), as&iacute; como por reclamaciones laborales a cargo del ASOCIADO, la COOPERATIVA tendr&aacute; un (1) a&ntilde;o m&aacute;s a partir de la fecha de vencimiento del respectivo t&eacute;rmino de prescripci&oacute;n para presentar al ASOCIADO una reclamaci&oacute;n bajo esta cl&aacute;usula con base en dichas reclamaciones. <strong>DECIMA PRIMERA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA SEGUNDA</strong>: Ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes aprobados el 10 de marzo de 2019, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA TERCERA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA CUARTA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA QUINTA</strong>: ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos, en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA SEXTA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA S&Eacute;PTIMA</strong>: El ASOCIADO se compromete con la COOPERATIVA a dar aviso inmediato de los cambios de direcci&oacute;n e informaci&oacute;n personal. <strong>DECIMA OCTAVA</strong>: Se aclara que la venta del veh&iacute;culo a terceros, no implica para el asociado vendedor la cesi&oacute;n de sus aportes sociales, ni dem&aacute;s compromisos econ&oacute;micos que pueda llegar a tener. Tampoco implica la venta la a capacidad transportadora, pues solamente el asociado tiene pleno dominio sobre el veh&iacute;culo. <strong>DECIMA NOVENA</strong>: Quien se constituya en nuevo propietario, debe asociarse a la cooperativa de inmediato para tener derecho a la utilizaci&oacute;n de la capacidad transportadora y se someter&aacute; a los requisitos y tr&aacute;mites de ingresos a la COOPERATIVA como nuevo asociado, reserv&aacute;ndose la COOPERATIVA los derechos de admisi&oacute;n. La capacidad transportadora ser&aacute; siempre de la COOPERATIVA quien tiene la postad de poderla asignar de forma temporal a quienes cumplan con los requisitos para ser asociado. <strong>VIG&Eacute;SIMA</strong>: el presente contrato de vinculaci&oacute;n tiene una duraci&oacute;n de un a&ntilde;o contados a partir de la firma del mismo, no se aplicar&aacute;n prorrogas ni adiciones, adem&aacute;s no es necesario preavisar la terminaci&oacute;n del mismo ya que esta se entiende comunicada y a satisfacci&oacute;n con la firma del presente contrato.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br><strong>FECHA</strong>: fechaContrato<br><strong>DIRECCION</strong>: direccionAsociado<br><strong>TELEFONO(S)</strong>: telefonoAsociado<br><strong>DOCUMENTOS ADICIONALES</strong>: documentosAdionales<br><strong>OBSERVACIONES</strong>: observacionGeneral</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<table style=\"border-collapse: collapse; width: 100.008%;\" border=\"0\"><colgroup><col style=\"width: 49.9473%;\"><col style=\"width: 49.9473%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td><strong>nombreAsociado </strong></td>\r\n<td><strong>nombreGerente</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Asociado</td>\r\n<td>Gerente</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2023-11-09 09:32:06', '2023-11-09 09:32:06'),
(2, 'pagareColocacion', 'PAGARÉ NÚMERO  numeroPagare', '<table style=\"border-collapse: collapse; width: 100.008%;\" border=\"0\"><colgroup><col style=\"width: 27.3991%;\"><col style=\"width: 25.5309%;\"><col style=\"width: 25.5303%;\"><col style=\"width: 21.5878%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td>N&uacute;mero de pagar&eacute;:</td>\r\n<td><strong>numeroPagare</strong></td>\r\n<td>Valor del cr&eacute;dito:</td>\r\n<td>$ <strong>valorCredito</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Fecha de la solicitud:</td>\r\n<td>fechaSolicitud</td>\r\n<td>Fecha del desembolso:</td>\r\n<td><strong>fechaDesembolso</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Fecha de la primera cuota:</td>\r\n<td>fechaPrimeraCuota</td>\r\n<td>Fecha de la &uacute;ltima cuota:</td>\r\n<td>fechaUltimaCuota</td>\r\n</tr>\r\n<tr>\r\n<td>Inter&eacute;s mensual:</td>\r\n<td>interesMensual %</td>\r\n<td>N&uacute;mero de cuotas:</td>\r\n<td>numeroCuota</td>\r\n</tr>\r\n<tr>\r\n<td>Destinaci&oacute;n del cr&eacute;dito:</td>\r\n<td colspan=\"3\">destinacionCredito</td>\r\n</tr>\r\n<tr>\r\n<td>Referencia:</td>\r\n<td>referenciaCredito</td>\r\n<td>Garant&iacute;a:</td>\r\n<td>garantiaCredito</td>\r\n</tr>\r\n<tr>\r\n<td>N&uacute;mero interno:</td>\r\n<td>numeroInternoVehiculo</td>\r\n<td>Placa:</td>\r\n<td>placaVehiculo</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">El Suscrito, <strong>nombreAsociado </strong>identificado con tpDocumentoAsociado <strong>documentoAsociado</strong>, deudor(a) principal me obligo a pagar solidaria e incondicionalmente en dinero en efectivo a COOTRANSHACARITAMA LTDA, en su oficina de Oca&ntilde;a N.S, a su orden o a quien represente sus derechos, la suma de ($ <strong>valorCredito</strong>), (<strong>valorEnLetras</strong>) moneda legal recibida en calidad de mutuo o pr&eacute;stamo a inter&eacute;s. INTERESES: Que sobre la suma debida reconocer&eacute; intereses equivalentes al <strong>interesMensual</strong>% mensual, sobre el saldo de capital insoluto, los cuales se liquidar&aacute;n y pagar&aacute;n mes vencido, junto con la cuota mensual correspondiente al mes de causaci&oacute;n. En caso de mora, reconocer&eacute; intereses moratorios del <strong>interesMoratorio</strong>% mensual. PARAGRAFO: En caso que la tasa de inter&eacute;s corriente y/o moratorio pactado, sobrepase los topes m&aacute;ximos permitidos por las disposiciones comerciales, dichas tasas se ajustar&aacute;n mensualmente a los m&aacute;ximos legales. PLAZO: Que pagar&eacute; la suma indicada en la cl&aacute;usula anterior mediante instalamentos mensuales sucesivos y en <strong>numeroCuota </strong>cuota(s), correspondientes cada una a la cantidad de $ <strong>valorCuota</strong>,&nbsp; m&aacute;s los intereses corrientes sobre el saldo, a partir del d&iacute;a fechaLargaPrestamo. VENCIMIENTO DEL PLAZO: Autorizo a COOTRANSHACARITAMA LTDA para declarar vencido totalmente el plazo de esta obligaci&oacute;n y exigir el pago inmediato del saldo, intereses, gastos judiciales y de los que se causen por el cobro de la obligaci&oacute;n, en cualquiera de los siguientes casos: a) Por mora de una o m&aacute;s cuotas de capital o de los intereses de esta o cualquier obligaci&oacute;n que, conjunta o separadamente, tenga contra&iacute;da a favor de COOTRANSHACARITAMA LTDA ; b) Si fuere demandado judicialmente o si los bienes de cualquiera de los otorgantes son embargados o perseguidos por la v&iacute;a judicial; c) Por muerte, concordato, quiebra, concurso de acreedores, disoluci&oacute;n, liquidaci&oacute;n o inhabilidad de uno de los otorgantes; d) Si mis activos se disminuyen, los bienes dados en garant&iacute;a se gravan o enajenan en todo o en parte o dejan de ser respaldo suficiente de la(s) obligaci&oacute;n(es) adquirida(s) o si incumpliera la obligaci&oacute;n de mantener actualizada la garant&iacute;a; e) Si la inversi&oacute;n del cr&eacute;dito fuese diferente de la convenida o de la mencionada en la solicitud del pr&eacute;stamo; f) si no actualizo(amos) oportunamente la informaci&oacute;n legal y financiera en los plazos que determine COOTRANSHACARITAMA LTDA; g) Las dem&aacute;s que las reglamentaciones internas de COOTRANSHACARITAMA LTDA contemplen. GASTOS E IMPUESTOS: Todos los gastos e impuestos que cause este pagar&eacute; sean de mi cargo, as&iacute; como los honorarios de abogado, costos judiciales y dem&aacute;s gastos que se generen. Me oblig&oacute; a cancelar las primas de seguros en las condiciones establecidas en las p&oacute;lizas respectivas. Autorizo a COOTRANSHACARITAMA LTDA para debitar de la(s) cuenta(s) de dep&oacute;sito(s) en todas las modalidades que tenga cualquiera de los otorgantes, el importe de este t&iacute;tulo valor, la cuota o cuotas respectivas, los intereses, primas de seguros y dem&aacute;s gastos o impuestos causados por esta obligaci&oacute;n. DESCUENTOS LABORALES: De acuerdo con lo previsto en el art&iacute;culo 142 de la ley 79 de 1988, autorizo (amos) irrevocablemente a la persona natural o jur&iacute;dica, p&uacute;blica o privada, a quien corresponda realizarme el pago de cualquier cantidad de dinero por concepto laboral o prestaciones, para que deduzca o retenga de estos valores, sin perjuicio de las acciones judiciales que quiera iniciar directamente sin hacer valer la autorizaci&oacute;n. Se suscribe en la ciudad de Oca&ntilde;a a los fechaLargaDesembolso.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2023-11-09 09:32:06', '2023-11-09 09:32:06'),
(3, 'cartaInstrucciones', 'REFERENCIA: CARTA DE INSTRUCCIONES', '<p style=\"text-align: justify;\">Yo, <strong>nombreAsociado &nbsp;</strong>mayor de edad, identificado como aparece al pie de mi firma, actuando en nombre propio, por medio del presente escrito manifiesto que le faculto a usted, de manera permanente e irrevocable para que, en caso de incumplimiento en el pago oportuno de alguna de las obligaciones que hemos adquirido con usted, derivadas de los negocios comerciales y contractuales bien sean verbales o escritos; sin previo aviso, proceda a llenar los espacios en blanco La letra del pagar&eacute; No. <strong>numeroPagare </strong>que he suscrito en la fecha a su favor y que se anexa, con el fin de convertir el pagar&eacute;, en un documento que presta m&eacute;rito ejecutivo y que est&aacute; sujeto a los par&aacute;metros legales del Art&iacute;culo 622 del C&oacute;digo de Comercio.</p>\r\n<p style=\"text-align: justify;\">1. El espacio correspondiente a &ldquo;la suma cierta de&rdquo; se llenar&aacute; por una suma igual a la que resulte pendiente de pago de todas la obligaciones contra&iacute;das con el acreedor, por concepto de capital, intereses, seguros, cobranza extrajudicial, seg&uacute;n la contabilidad del acreedor a la fecha en que sea llenado el pagar&eacute;.</p>\r\n<p style=\"text-align: justify;\">2. El espacio correspondiente a la fecha en que se debe hacer el pago, se llenar&aacute; con la fecha correspondiente al d&iacute;a en que sea llenado el pagar&eacute;, fecha que se entiende que es la de su vencimiento.</p>\r\n<p style=\"text-align: justify;\">En constancia de lo anterior firmamos la presente autorizaci&oacute;n, el d&iacute;a fechaLargaPrestamo.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">EL DEUDOR,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2023-11-09 09:32:06', '2023-11-09 09:32:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacionnotificacioncorreo`
--

CREATE TABLE `informacionnotificacioncorreo` (
  `innocoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla informacion notificación correo',
  `innoconombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se consulta desde el sistema',
  `innocoasunto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto de la información que lleva notificación del correo',
  `innococontenido` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contenido de la información que lleva notificación del correo',
  `innocoenviarpiepagina` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si se va incluir el contenido de pie de pagina',
  `innocoenviarcopia` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina se se desea enviar copia al administrador',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacionnotificacioncorreo`
--

INSERT INTO `informacionnotificacioncorreo` (`innocoid`, `innoconombre`, `innocoasunto`, `innococontenido`, `innocoenviarpiepagina`, `innocoenviarcopia`, `created_at`, `updated_at`) VALUES
(1, 'piePaginaCorreo', 'Pie página correo', '<p style=\"text-align: justify;\"><strong>Para su inter&eacute;s</strong>:&nbsp;<br /><br /><span style=\"font-size: 10pt;\">1. Este correo fue generado autom&aacute;ticamente, por favor no responda a &eacute;l.</span><br /><span style=\"font-size: 10pt;\">2. La informaci&oacute;n contenida en esta comunicaci&oacute;n es confidencial y s&oacute;lo puede ser utilizada por la persona natural o jur&iacute;dica a la cual est&aacute; dirigida.</span><br /><span style=\"font-size: 10pt;\">3. Si no es el destinatario autorizado, cualquier retenci&oacute;n, difusi&oacute;n, distribuci&oacute;n o copia de este mensaje, se encuentra prohibida y sancionada por la ley.</span><br /><span style=\"font-size: 10pt;\">4. Si por error recibe este mensaje, favor reenviar y borrar el mensaje recibido inmediatamente\". (Resoluci&oacute;n No. 089 de 2003 - Reglamento para el uso de Internet y Correo Electr&oacute;nico en el AGN. Art&iacute;culo 3&deg; numeral 5.</span></p>', 0, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(2, 'registroUsuario', '¡Bienvenido al CRM de siglaCooperativa!', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreUsuario</strong>, Es un placer informarle que a partir de este momento, la <strong>nombreEmpresa </strong>ha implementado un nuevo y avanzado Sistema de Gesti&oacute;n de Relaciones con el Cliente (CRM). Este sistema ha sido dise&ntilde;ado para mejorar significativamente nuestros procesos internos y proporcionarle a usted, como usuario, una experiencia m&aacute;s eficiente y personalizada.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">A continuaci&oacute;n, encontrar&aacute; los detalles clave de esta actualizaci&oacute;n:<br><br></p>\r\n<p class=\"MsoNormal\">URL del Sistema: <strong><a href=\"urlSistema\" target=\"_blank\">urlSistema</a> </strong><br>Usuario del CRM: <strong>usuarioSistema</strong><br>Credenciales de acceso: <strong>contrasenaSistema</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>Este nuevo CRM le permitir&aacute;:</p>\r\n<ul>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Acceder a su informaci&oacute;n y estado de cuenta de manera r&aacute;pida y sencilla.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Realizar seguimiento de sus transacciones y solicitudes.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Comunicarse de manera efectiva con nuestro equipo de trabajo.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Acceder a servicios y recursos exclusivos para miembros de la Cooperativa.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Le recomendamos que cambie su contrase&ntilde;a inicial despu&eacute;s de su primer inicio de sesi&oacute;n para garantizar la seguridad de su cuenta.</li>\r\n</ul>\r\n<p>Estamos comprometidos en brindarle un servicio de la m&aacute;s alta calidad, y creemos que esta actualizaci&oacute;n nos permitir&aacute; servirle de manera m&aacute;s efectiva. Si tiene alguna pregunta o necesita asistencia para familiarizarse con el nuevo sistema, no dude en ponerse en contacto con nuestro equipo de tecn&oacute;loga, quienes estar&aacute;n encantados de ayudarle.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Agradecemos su confianza en la nombreEmpresa y esperamos que este nuevo CRM mejore su experiencia con nosotros. Estamos seguros de que encontrar&aacute; el sistema m&aacute;s intuitivo y &uacute;til.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&iexcl;Bienvenido al futuro de la nombreEmpresa!</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\"><strong>nombreGerente</strong><br><strong>Gerente general </strong><br><strong>nombreEmpresa</strong></p>\r\n<p>&nbsp;</p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(3, 'solicitaFirmaDocumento', 'Solicitud de firma para del documento con referencia numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreFeje</strong>, por medio de la presente me permito informar que se ha generado un documento importante que requiere su aprobaci&oacute;n y firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Detalles del documento:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tipo de documento: <strong>tipoDocumental</strong><br>N&uacute;mero de documento: <strong>numeroDocumental</strong><br>Fecha de generaci&oacute;n: <strong>fechaDocumento</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, revise el documento y proceda a firmarlo utilizando la plataforma de CRM de nuestra cooperativa. Si tiene alguna pregunta o inquietud sobre el contenido del documento o el proceso de firma, no dude en contactarme.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Su cooperaci&oacute;n en este asunto es altamente apreciada y fundamental para avanzar en este proceso de manera oportuna.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Quedo a su disposici&oacute;n para cualquier aclaraci&oacute;n adicional que pueda necesitar.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><em><strong>nombreUsuario</strong></em><br><em><strong>cargoUsuario</strong></em></p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(4, 'anularSolicitudFirmaDocumento', 'Revisión y ajustes necesarios para documento con referencia numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreFeje</strong>, le informo que el documento <strong>tipoDocumental </strong>con el n&uacute;mero <strong>numeroDocumental</strong>, que est&aacute; programado para su firma, requiere algunos ajustes y revisiones antes de que podamos proceder con el proceso de firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Detalles del documento:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tipo de documento: <strong>tipoDocumental</strong><br>N&uacute;mero de documento: <strong>numeroDocumental</strong><br>Fecha de generaci&oacute;n: <strong>fechaDocumento</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Los ajustes necesarios est&aacute;n relacionados con &ldquo;<em>observacionAnulacionFirma</em>&rdquo;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tan pronto como realice los ajustes, estaremos listos para proceder con el proceso de firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Quedo a su disposici&oacute;n para cualquier aclaraci&oacute;n adicional que pueda necesitar.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; text-align: justify; line-height: normal;\"><span style=\"font-size: 12.0pt; font-family: \'Times New Roman\',serif; mso-fareast-font-family: \'Times New Roman\'; mso-font-kerning: 0pt; mso-ligatures: none; mso-fareast-language: ES-CO;\">Atentamente,<br><br></span></p>\r\n<p class=\"MsoNormal\" style=\"mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; text-align: justify; line-height: normal;\"><strong><em>nombreUsuario</em></strong><br><strong><em>cargoUsuario</em></strong></p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(5, 'notificarEnvioDocumento', 'Envío de documento digital de la Cooperativa siglaCooperativa con referencia numeroDocumental', '<p style=\"text-align: justify;\">Estimado/a <strong>nombreUsuario</strong>,</p>\r\n<p style=\"text-align: justify;\">Por medio de la presente nos permitimos informar que la dependencia de <strong>nombreDependencia </strong>de la <strong>nombreEmpresa </strong>ha enviado un documento en formato digital. Este archivo adjunto contiene la informaci&oacute;n requerida y puede ser revisado en su dispositivo electr&oacute;nico.</p>\r\n<p style=\"text-align: justify;\"><br>Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\"><br>Agradecemos su colaboraci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p>Atentamente,</p>\r\n<p>&nbsp;</p>\r\n<p><em><strong>jefeDependencia</strong></em><br><em><strong>nombreEmpresa</strong></em><br><em><strong>nombreDependencia</strong></em></p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(6, 'notificarFirmadoDocumento', 'Solicitud de token de verificación para el firmado del documento numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreJefe</strong>, para avanzar con el proceso de firma electr&oacute;nica del documento <strong>numeroDocumental</strong>, es necesario que ingrese el siguiente c&oacute;digo de verificaci&oacute;n:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><strong>C&oacute;digo de Verificaci&oacute;n:&nbsp;<em>tokenAcceso</em></strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>Tenga en cuenta que este token de verificaci&oacute;n ser&aacute; v&aacute;lido durante los pr&oacute;ximos&nbsp;<strong>tiempoToken </strong>minutos. Si transcurre este tiempo sin completar el proceso, deber&aacute; solicitar un nuevo token.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, acceda a nuestra plataforma y proporcione el token que le hemos proporcionado. Luego, haga clic en el bot&oacute;n de firma para completar el proceso.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Gracias por su colaboraci&oacute;n y compromiso con la seguridad de nuestros servicios.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Administrador del CRM</p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(7, 'notificarRegistroRadicado', 'Nuevo documento radicado en la Ventanilla Única Virtual con consecutivo numeroRadicado', '<p style=\"text-align: justify;\">Estimado <strong>nombreUsuario</strong>, agradecemos el uso del servicio prestado por la Ventanilla &Uacute;nica de la nombreEmpresa. Queremos informarle que su documento ha sido radicado con los siguientes detalles:</p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de radicado:&nbsp; &nbsp;<strong>numeroRadicado</strong></em><br><em>Fecha de radicado:&nbsp; &nbsp; &nbsp; &nbsp;<strong>fechaRadicado</strong></em><br><em>Destino:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>nombreDependencia</strong></em><br><em>Radicado por:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreFuncionario</strong></em></p>\r\n<p style=\"text-align: justify;\">Le recordamos que la funci&oacute;n de la Ventanilla &Uacute;nica es recibir, radicar y redireccionar su solicitud, cumpliendo con los criterios b&aacute;sicos, ante la instancia correspondiente. La respuesta ser&aacute; proporcionada por la oficina indicada en su comunicaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Quedamos a su disposici&oacute;n para cualquier consulta adicional.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Ventanilla &Uacute;nica<br>nombreEmpresa</p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(8, 'notificarRadicadoDocumento', 'Nuevo documento radicado con consecutivo numeroRadicado', '<p style=\"text-align: justify;\">Estimado usuario de <strong>nombreDependencia</strong>, les informamos que ha llegado un nuevo documento a trav&eacute;s de la Ventanilla &Uacute;nica, el cual ha sido debidamente radicado con los siguientes detalles:</p>\r\n<p style=\"text-align: justify;\">Radicado:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>numeroRadicado</strong><br>Fecha de recepci&oacute;n:&nbsp; <strong>fechaRadicado</strong><br>Destino:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreDependencia</strong><br>Radicado por:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>nombreFuncionario</strong></p>\r\n<p style=\"text-align: justify;\">Queremos recordarles que, de acuerdo con los procedimientos, se tiene un plazo de 15 d&iacute;as h&aacute;biles para proporcionar una respuesta a esta solicitud. Este plazo iniciar&aacute; a partir del d&iacute;a siguiente a la radicaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Por favor, tomen en cuenta que para acceder al contenido completo, les recomendamos ingresar al nuestro CRM institucional.</p>\r\n<p style=\"text-align: justify;\">Agradecemos su compromiso y dedicaci&oacute;n en pro del mejoramiento continuo de nuestros procesos.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Ventanilla &Uacute;nica<br>nombreEmpresa</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40'),
(9, 'notificarFirmaTipoDocumental', 'Proceso de firmado exitoso del tipo documental tipoDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Estimado usuario de la dependencia <strong>nombreDependencia</strong>, por medio de la presente me permito informar que el tipo documental \"<strong>tipoDocumental</strong>\"&nbsp;<strong>&nbsp;</strong>ha sido correctamente firmado y est&aacute; listo para avanzar al siguiente paso en el proceso.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Por favor, procede con el sellamiento y cualquier otra acci&oacute;n necesaria para completar este proceso de manera satisfactoria.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarme. Agradezco tu atenci&oacute;n y dedicaci&oacute;n en este asunto.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Atentamente,<br><br></span></p>\r\n<p class=\"MsoNormal\"><em><strong><span lang=\"ES\" style=\"mso-ansi-language: ES;\">nombreJefe<br></span><span lang=\"ES\" style=\"mso-ansi-language: ES;\">cargoJefe</span></strong></em></p>', 1, 0, '2023-11-09 09:29:40', '2023-11-09 09:29:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresosistema`
--

CREATE TABLE `ingresosistema` (
  `ingsisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla ingreso sistema',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `ingsisipacceso` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ip de la cual accede el usuario al sistema',
  `ingsisfechahoraingreso` datetime NOT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `ingsisfechahorasalida` datetime DEFAULT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingresosistema`
--

INSERT INTO `ingresosistema` (`ingsisid`, `usuaid`, `ingsisipacceso`, `ingsisfechahoraingreso`, `ingsisfechahorasalida`, `created_at`, `updated_at`) VALUES
(1, 1, '127.0.0.1', '2023-11-09 04:53:31', NULL, '2023-11-09 09:53:31', '2023-11-09 09:53:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentosfallidos`
--

CREATE TABLE `intentosfallidos` (
  `intfalid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla intentos fallidos',
  `intfalusurio` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Usuario que accede al sistema',
  `intfalipacceso` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ip de la cual accede el usuario al sistema',
  `intfalfecha` datetime NOT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineacredito`
--

CREATE TABLE `lineacredito` (
  `lincreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla línea de crédito',
  `lincrenombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la línea de crédito',
  `lincretasanominal` decimal(6,2) DEFAULT NULL COMMENT 'Tasa nominal para línea de crédito',
  `lincremontominimo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Monto mínimo de la línea de crédito',
  `lincremontomaximo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Monto máximo de la línea de crédito',
  `lincreplazomaximo` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'Plazo máximo en meses de la línea de crédito',
  `lincreactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la línea de crédito se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2023_08_28_084042_create_tipo_identificacion', 1),
(3, '2023_08_28_084043_create_tipo_documental', 1),
(4, '2023_08_28_084044_create_tipo_despedida', 1),
(5, '2023_08_28_084045_create_tipo_saludo', 1),
(6, '2023_08_28_084046_create_tipo_estado_documento', 1),
(7, '2023_08_28_084047_create_tipo_destino', 1),
(8, '2023_08_28_084048_create_tipo_persona_documental', 1),
(9, '2023_08_28_084049_create_tipo_tramite', 1),
(10, '2023_08_28_084050_create_tipo_medio', 1),
(11, '2023_08_28_084051_create_tipo_acta', 1),
(12, '2023_08_28_084052_create_tipo_persona', 1),
(13, '2023_08_28_084053_create_tipo_estado_radicacion_documento_entrante', 1),
(14, '2023_08_28_084054_create_tipo_estante_archivador', 1),
(15, '2023_08_28_084055_create_tipo_caja_ubicacion', 1),
(16, '2023_08_28_084056_create_tipo_carpeta_ubicacion', 1),
(17, '2023_08_28_085009_create_informacion_notificacion_correo', 1),
(18, '2023_08_28_085010_create_informacion_configuracion_correo', 1),
(19, '2023_08_28_085011_create_cargo_laboral', 1),
(20, '2023_08_28_085012_create_departamento', 1),
(21, '2023_08_28_085013_create_municipio', 1),
(22, '2023_08_28_085014_create_persona', 1),
(23, '2023_08_28_085110_create_usuario', 1),
(24, '2023_08_28_085111_create_ingreso_sistema', 1),
(25, '2023_08_28_085112_create_intentos_fallidos', 1),
(26, '2023_08_28_085113_create_historial_contrasena', 1),
(27, '2023_08_28_086105_create_serie_documental', 1),
(28, '2023_08_28_086106_create_sub_serie_documental', 1),
(29, '2023_08_28_086110_create_dependencia', 1),
(30, '2023_08_28_086111_create_dependencia_persona', 1),
(31, '2023_08_28_086112_create_dependencia_sub_serie_documental', 1),
(32, '2023_08_28_086114_create_token_firma_persona', 1),
(33, '2023_08_31_043412_create_modulo', 1),
(34, '2023_08_31_043621_create_rol', 1),
(35, '2023_08_31_043639_create_funcionalidad', 1),
(36, '2023_08_31_043658_create_rol_funcionalidad', 1),
(37, '2023_08_31_043659_create_usuario_rol', 1),
(38, '2023_09_02_140139_create_empresa', 1),
(39, '2023_09_02_140140_create_festivo', 1),
(40, '2023_09_07_050956_create_codigo_documental', 1),
(41, '2023_09_07_052612_create_codigo_documental_proceso', 1),
(42, '2023_09_07_052618_create_codigo_documental_proceso_acta', 1),
(43, '2023_09_07_055753_create_codigo_documental_proceso_certificado', 1),
(44, '2023_09_07_055759_create_codigo_documental_proceso_circular', 1),
(45, '2023_09_07_055806_create_codigo_documental_proceso_citacion', 1),
(46, '2023_09_07_055812_create_codigo_documental_proceso_constancia', 1),
(47, '2023_09_07_055817_create_codigo_documental_proceso_oficio', 1),
(48, '2023_09_09_102437_create_codigo_documental_proceso_firma', 1),
(49, '2023_09_09_102822_create_codigo_documental_proceso_anexo', 1),
(50, '2023_09_09_103332_create_codigo_documental_proceso_copia', 1),
(51, '2023_09_09_103333_create_codigo_documental_proceso_cambio_estado', 1),
(52, '2023_09_09_103333_create_codigo_documental_proceso_compartido', 1),
(53, '2023_09_09_103335_create_persona_radica_documento', 1),
(54, '2023_09_09_103336_create_radicacion_documento_entrante', 1),
(55, '2023_09_09_103337_create_radicacion_documento_entrante_anexo', 1),
(56, '2023_09_09_103338_create_radicacion_documento_entrante_dependencia', 1),
(57, '2023_09_09_103339_create_radicacion_documento_entrante_cambio_estado', 1),
(58, '2023_09_09_103340_create_codigo_documental_proceso_radicacion_documento_entrante', 1),
(59, '2023_09_09_103345_create_archivo_historico', 1),
(60, '2023_09_09_103346_create_archivo_historico_digitalizado', 1),
(61, '2023_10_18_043200_create_informacion_general_pdf', 1),
(62, '2023_10_18_043301_create_tipo_estado_asociado', 1),
(63, '2023_10_18_043302_create_tipo_estado_solicitud_credito', 1),
(64, '2023_10_18_043303_create_tipo_estado_conductor', 1),
(65, '2023_10_18_043304_create_tipo_estado_colocacion', 1),
(66, '2023_10_18_043312_create_tipo_vehiculo', 1),
(67, '2023_10_18_043313_create_tipo_referencia_vehiculo', 1),
(68, '2023_10_18_043314_create_tipo_marca_vehiculo', 1),
(69, '2023_10_18_043315_create_tipo_conductor', 1),
(70, '2023_10_18_043316_create_tipo_color_vehiculo', 1),
(71, '2023_10_18_043317_create_tipo_carroceria_vehiculo', 1),
(72, '2023_10_18_043318_create_tipo_combustible_vehiculo', 1),
(73, '2023_10_18_043319_create_tipo_estado_vehiculo', 1),
(74, '2023_10_18_043320_create_tipo_modalidad_vehiculo', 1),
(75, '2023_10_18_043321_create_tipo_categoria_licencia', 1),
(76, '2023_10_18_043321_create_tipo_servicio_vehiculo', 1),
(77, '2023_10_18_043409_create_agencia', 1),
(78, '2023_10_18_043410_create_conductor', 1),
(79, '2023_10_18_043411_create_conductor_licencia', 1),
(80, '2023_10_18_043412_create_conductor_cambio_estado', 1),
(81, '2023_10_18_043415_create_vehiculo', 1),
(82, '2023_10_18_043416_create_vehiculo_crt', 1),
(83, '2023_10_18_043417_create_vehiculo_poliza', 1),
(84, '2023_10_18_043418_create_vehiculo_soat', 1),
(85, '2023_10_18_043419_create_vehiculo_tarjeta_operacion', 1),
(86, '2023_10_18_043420_create_conductor_vehiculo', 1),
(87, '2023_10_18_043421_create_vehiculo_cambio_estado', 1),
(88, '2023_10_18_043510_create_asociado', 1),
(89, '2023_10_18_043511_create_asociado_vehiculo', 1),
(90, '2023_10_18_043512_create_asociado_cambio_estado', 1),
(91, '2023_10_18_053310_create_linea_credito', 1),
(92, '2023_10_18_053311_create_solicitud_credito', 1),
(93, '2023_10_18_053315_create_solicitud_credito_cambio_estado', 1),
(94, '2023_10_18_053320_create_colocacion', 1),
(95, '2023_10_18_053321_create_colocacion_liquidacion', 1),
(96, '2023_10_18_053322_create_colocacion_cambio_estado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `moduid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla módulo',
  `modunombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del módulo',
  `moduicono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clase de css para montar en el link del módulo',
  `moduorden` smallint(6) NOT NULL COMMENT 'Orden del en el árbol del menú que se muesra el módulo',
  `moduactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el módulo encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`moduid`, `modunombre`, `moduicono`, `moduorden`, `moduactivo`, `created_at`, `updated_at`) VALUES
(1, 'Configuración', 'settings_applications', 1, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(2, 'Gestionar', 'ac_unit_icon', 2, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(3, 'Producción documental', 'menu_book_icon', 3, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(4, 'Radicación', 'folder_special_icon', 4, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(5, 'Archivo histórico', 'insert_page_break_icon', 5, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(6, 'Asociados', 'person_search_icon', 6, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(7, 'Dirección transporte', 'drive_eta_icon', 7, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22'),
(8, 'Cartera', 'work_icon', 8, 1, '2023-11-09 09:31:22', '2023-11-09 09:31:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `muniid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla municipio',
  `munidepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `municodigo` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código del municipio',
  `muninombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del municipio',
  `munihacepresencia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la entidad hace presencia en este municipio',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`muniid`, `munidepaid`, `municodigo`, `muninombre`, `munihacepresencia`, `created_at`, `updated_at`) VALUES
(1, 1, '05001', 'Medellin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(2, 1, '05002', 'Abejorral', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(3, 1, '05004', 'Abriaqui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(4, 1, '05021', 'Alejandria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(5, 1, '05030', 'Amaga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(6, 1, '05031', 'Amalfi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(7, 1, '05034', 'Andes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(8, 1, '05036', 'Angelopolis', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(9, 1, '05038', 'Angostura', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(10, 1, '05040', 'Anori', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(11, 1, '05042', 'Santafe de Antioquia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(12, 1, '05044', 'Anza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(13, 1, '05045', 'Apartado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(14, 1, '05051', 'Arboletes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(15, 1, '05055', 'Argelia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(16, 1, '05059', 'Armenia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(17, 1, '05079', 'Barbosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(18, 1, '05086', 'Belmira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(19, 1, '05088', 'Bello', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(20, 1, '05091', 'Betania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(21, 1, '05093', 'Betulia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(22, 1, '05101', 'Ciudad Bolivar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(23, 1, '05107', 'Briceño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(24, 1, '05113', 'Buritica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(25, 1, '05120', 'Caceres', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(26, 1, '05125', 'Caicedo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(27, 1, '05129', 'Caldas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(28, 1, '05134', 'Campamento', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(29, 1, '05138', 'Cañasgordas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(30, 1, '05142', 'Caracoli', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(31, 1, '05145', 'Caramanta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(32, 1, '05147', 'Carepa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(33, 1, '05148', 'El Carmen de Viboral', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(34, 1, '05150', 'Carolina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(35, 1, '05154', 'Caucasia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(36, 1, '05172', 'Chigorodo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(37, 1, '05190', 'Cisneros', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(38, 1, '05197', 'Cocorna', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(39, 1, '05206', 'Concepcion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(40, 1, '05209', 'Concordia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(41, 1, '05212', 'Copacabana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(42, 1, '05234', 'Dabeiba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(43, 1, '05237', 'Don Matias', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(44, 1, '05240', 'Ebejico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(45, 1, '05250', 'El Bagre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(46, 1, '05264', 'Entrerrios', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(47, 1, '05266', 'Envigado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(48, 1, '05282', 'Fredonia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(49, 1, '05284', 'Frontino', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(50, 1, '05306', 'Giraldo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(51, 1, '05308', 'Girardota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(52, 1, '05310', 'Gomez Plata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(53, 1, '05313', 'Granada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(54, 1, '05315', 'Guadalupe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(55, 1, '05318', 'Guarne', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(56, 1, '05321', 'Guatape', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(57, 1, '05347', 'Heliconia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(58, 1, '05353', 'Hispania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(59, 1, '05360', 'Itagui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(60, 1, '05361', 'Ituango', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(61, 1, '05364', 'Jardin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(62, 1, '05368', 'Jerico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(63, 1, '05376', 'La Ceja', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(64, 1, '05380', 'La Estrella', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(65, 1, '05390', 'La Pintada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(66, 1, '05400', 'La Union', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(67, 1, '05411', 'Liborina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(68, 1, '05425', 'Maceo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(69, 1, '05440', 'Marinilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(70, 1, '05467', 'Montebello', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(71, 1, '05475', 'Murindo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(72, 1, '05480', 'Mutata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(73, 1, '05483', 'Nariño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(74, 1, '05490', 'Necocli', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(75, 1, '05495', 'Nechi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(76, 1, '05501', 'Olaya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(77, 1, '05541', 'Peðol', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(78, 1, '05543', 'Peque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(79, 1, '05576', 'Pueblorrico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(80, 1, '05579', 'Puerto Berrio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(81, 1, '05585', 'Puerto Nare', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(82, 1, '05591', 'Puerto Triunfo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(83, 1, '05604', 'Remedios', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(84, 1, '05607', 'Retiro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(85, 1, '05615', 'Rionegro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(86, 1, '05628', 'Sabanalarga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(87, 1, '05631', 'Sabaneta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(88, 1, '05642', 'Salgar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(89, 1, '05647', 'San Andres De Cuerquia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(90, 1, '05649', 'San Carlos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(91, 1, '05652', 'San Francisco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(92, 1, '05656', 'San Jeronimo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(93, 1, '05658', 'San Jose De La Montaña', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(94, 1, '05659', 'San Juan De Uraba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(95, 1, '05660', 'San Luis', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(96, 1, '05664', 'San Pedro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(97, 1, '05665', 'San Pedro De Uraba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(98, 1, '05667', 'San Rafael', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(99, 1, '05670', 'San Roque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(100, 1, '05674', 'San Vicente', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(101, 1, '05679', 'Santa Barbara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(102, 1, '05686', 'Santa Rosa De Osos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(103, 1, '05690', 'Santo Domingo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(104, 1, '05697', 'El Santuario', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(105, 1, '05736', 'Segovia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(106, 1, '05756', 'Sonson', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(107, 1, '05761', 'Sopetran', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(108, 1, '05789', 'Tamesis', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(109, 1, '05790', 'Taraza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(110, 1, '05792', 'Tarso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(111, 1, '05809', 'Titiribi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(112, 1, '05819', 'Toledo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(113, 1, '05837', 'Turbo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(114, 1, '05842', 'Uramita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(115, 1, '05847', 'Urrao', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(116, 1, '05854', 'Valdivia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(117, 1, '05856', 'Valparaiso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(118, 1, '05858', 'Vegachi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(119, 1, '05861', 'Venecia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(120, 1, '05873', 'Vigia Del Fuerte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(121, 1, '05885', 'Yali', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(122, 1, '05887', 'Yarumal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(123, 1, '05890', 'Yolombo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(124, 1, '05893', 'Yondo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(125, 1, '05895', 'Zaragoza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(126, 2, '08001', 'Barranquilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(127, 2, '08078', 'Baranoa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(128, 2, '08137', 'Campo De La Cruz', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(129, 2, '08141', 'Candelaria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(130, 2, '08296', 'Galapa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(131, 2, '08372', 'Juan De Acosta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(132, 2, '08421', 'Luruaco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(133, 2, '08433', 'Malambo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(134, 2, '08436', 'Manati', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(135, 2, '08520', 'Palmar De Varela', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(136, 2, '08549', 'Piojo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(137, 2, '08558', 'Polonuevo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(138, 2, '08560', 'Ponedera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(139, 2, '08573', 'Puerto Colombia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(140, 2, '08606', 'Repelon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(141, 2, '08634', 'Sabanagrande', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(142, 2, '08638', 'Sabanalarga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(143, 2, '08675', 'Santa Lucia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(144, 2, '08685', 'Santo Tomas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(145, 2, '08758', 'Soledad', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(146, 2, '08770', 'Suan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(147, 2, '08832', 'Tubara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(148, 2, '08849', 'Usiacuri', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(149, 3, '11001', 'Bogotá, D.C.', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(150, 4, '13001', 'Cartagena', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(151, 4, '13006', 'Achi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(152, 4, '13030', 'Altos Del Rosario', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(153, 4, '13042', 'Arenal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(154, 4, '13052', 'Arjona', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(155, 4, '13062', 'Arroyohondo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(156, 4, '13074', 'Barranco De Loba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(157, 4, '13140', 'Calamar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(158, 4, '13160', 'Cantagallo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(159, 4, '13188', 'Cicuco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(160, 4, '13212', 'Cordoba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(161, 4, '13222', 'Clemencia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(162, 4, '13244', 'El Carmen De Bolivar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(163, 4, '13248', 'El Guamo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(164, 4, '13268', 'El Peñon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(165, 4, '13300', 'Hatillo De Loba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(166, 4, '13430', 'Magangue', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(167, 4, '13433', 'Mahates', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(168, 4, '13440', 'Margarita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(169, 4, '13442', 'Maria La Baja', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(170, 4, '13458', 'Montecristo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(171, 4, '13468', 'Mompos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(172, 4, '13490', 'Norosi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(173, 4, '13473', 'Morales', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(174, 4, '13549', 'Pinillos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(175, 4, '13580', 'Regidor', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(176, 4, '13600', 'Rio Viejo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(177, 4, '13620', 'San Cristobal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(178, 4, '13647', 'San Estanislao', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(179, 4, '13650', 'San Fernando', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(180, 4, '13654', 'San Jacinto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(181, 4, '13655', 'San Jacinto del Cauca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(182, 4, '13657', 'San Juan Nepomuceno', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(183, 4, '13667', 'San Martin de Loba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(184, 4, '13670', 'San Pablo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(185, 4, '13673', 'Santa Catalina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(186, 4, '13683', 'Santa Rosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(187, 4, '13688', 'Santa Rosa del Sur', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(188, 4, '13744', 'Simiti', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(189, 4, '13760', 'Soplaviento', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(190, 4, '13780', 'Talaigua Nuevo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(191, 4, '13810', 'Tiquisio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(192, 4, '13836', 'Turbaco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(193, 4, '13838', 'Turbana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(194, 4, '13873', 'Villanueva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(195, 4, '13894', 'Zambrano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(196, 5, '15001', 'Tunja', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(197, 5, '15022', 'Almeida', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(198, 5, '15047', 'Aquitania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(199, 5, '15051', 'Arcabuco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(200, 5, '15087', 'Belen', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(201, 5, '15090', 'Berbeo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(202, 5, '15092', 'Beteitiva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(203, 5, '15097', 'Boavita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(204, 5, '15104', 'Boyaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(205, 5, '15106', 'Briceño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(206, 5, '15109', 'Buenavista', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(207, 5, '15114', 'Busbanza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(208, 5, '15131', 'Caldas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(209, 5, '15135', 'Campohermoso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(210, 5, '15162', 'Cerinza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(211, 5, '15172', 'Chinavita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(212, 5, '15176', 'Chiquinquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(213, 5, '15180', 'Chiscas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(214, 5, '15183', 'Chita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(215, 5, '15185', 'Chitaraque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(216, 5, '15187', 'Chivata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(217, 5, '15189', 'Cienega', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(218, 5, '15204', 'Combita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(219, 5, '15212', 'Coper', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(220, 5, '15215', 'Corrales', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(221, 5, '15218', 'Covarachia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(222, 5, '15223', 'Cubara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(223, 5, '15224', 'Cucaita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(224, 5, '15226', 'Cuitiva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(225, 5, '15232', 'Chiquiza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(226, 5, '15236', 'Chivor', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(227, 5, '15238', 'Duitama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(228, 5, '15244', 'El Cocuy', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(229, 5, '15248', 'El Espino', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(230, 5, '15272', 'Firavitoba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(231, 5, '15276', 'Floresta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(232, 5, '15293', 'Gachantiva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(233, 5, '15296', 'Gameza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(234, 5, '15299', 'Garagoa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(235, 5, '15317', 'Guacamayas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(236, 5, '15322', 'Guateque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(237, 5, '15325', 'Guayata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(238, 5, '15332', 'Gsican', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(239, 5, '15362', 'Iza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(240, 5, '15367', 'Jenesano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(241, 5, '15368', 'Jerico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(242, 5, '15377', 'Labranzagrande', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(243, 5, '15380', 'La Capilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(244, 5, '15401', 'La Victoria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(245, 5, '15403', 'La Uvita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(246, 5, '15407', 'Villa de Leyva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(247, 5, '15425', 'Macanal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(248, 5, '15442', 'Maripi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(249, 5, '15455', 'Miraflores', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(250, 5, '15464', 'Mongua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(251, 5, '15466', 'Mongui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(252, 5, '15469', 'Moniquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(253, 5, '15476', 'Motavita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(254, 5, '15480', 'Muzo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(255, 5, '15491', 'Nobsa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(256, 5, '15494', 'Nuevo Colon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(257, 5, '15500', 'Oicata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(258, 5, '15507', 'Otanche', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(259, 5, '15511', 'Pachavita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(260, 5, '15514', 'Paez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(261, 5, '15516', 'Paipa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(262, 5, '15518', 'Pajarito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(263, 5, '15522', 'Panqueba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(264, 5, '15531', 'Pauna', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(265, 5, '15533', 'Paya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(266, 5, '15537', 'Paz De Rio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(267, 5, '15542', 'Pesca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(268, 5, '15550', 'Pisba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(269, 5, '15572', 'Puerto Boyaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(270, 5, '15580', 'Quipama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(271, 5, '15599', 'Ramiriqui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(272, 5, '15600', 'Raquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(273, 5, '15621', 'Rondon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(274, 5, '15632', 'Saboya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(275, 5, '15638', 'Sachica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(276, 5, '15646', 'Samaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(277, 5, '15660', 'San Eduardo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(278, 5, '15664', 'San Jose se Pare', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(279, 5, '15667', 'San Luis se Gaceno', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(280, 5, '15673', 'San Mateo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(281, 5, '15676', 'San Miguel se Sema', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(282, 5, '15681', 'San Pablo se Borbur', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(283, 5, '15686', 'Santana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(284, 5, '15690', 'Santa Maria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(285, 5, '15693', 'Santa Rosa se Viterbo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(286, 5, '15696', 'Santa Sofia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(287, 5, '15720', 'Sativanorte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(288, 5, '15723', 'Sativasur', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(289, 5, '15740', 'Siachoque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(290, 5, '15753', 'Soata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(291, 5, '15755', 'Socota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(292, 5, '15757', 'Socha', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(293, 5, '15759', 'Sogamoso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(294, 5, '15761', 'Somondoco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(295, 5, '15762', 'Sora', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(296, 5, '15763', 'Sotaquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(297, 5, '15764', 'Soraca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(298, 5, '15774', 'Susacon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(299, 5, '15776', 'Sutamarchan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(300, 5, '15778', 'Sutatenza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(301, 5, '15790', 'Tasco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(302, 5, '15798', 'Tenza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(303, 5, '15804', 'Tibana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(304, 5, '15806', 'Tibasosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(305, 5, '15808', 'Tinjaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(306, 5, '15810', 'Tipacoque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(307, 5, '15814', 'Toca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(308, 5, '15816', 'Togsi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(309, 5, '15820', 'Topaga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(310, 5, '15822', 'Tota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(311, 5, '15832', 'Tunungua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(312, 5, '15835', 'Turmeque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(313, 5, '15837', 'Tuta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(314, 5, '15839', 'Tutaza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(315, 5, '15842', 'Umbita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(316, 5, '15861', 'Ventaquemada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(317, 5, '15879', 'Viracacha', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(318, 5, '15897', 'Zetaquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(319, 6, '17001', 'Manizales', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(320, 6, '17013', 'Aguadas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(321, 6, '17042', 'Anserma', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(322, 6, '17050', 'Aranzazu', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(323, 6, '17088', 'Belalcazar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(324, 6, '17174', 'Chinchina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(325, 6, '17272', 'Filadelfia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(326, 6, '17380', 'La Dorada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(327, 6, '17388', 'La Merced', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(328, 6, '17433', 'Manzanares', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(329, 6, '17442', 'Marmato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(330, 6, '17444', 'Marquetalia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(331, 6, '17446', 'Marulanda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(332, 6, '17486', 'Neira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(333, 6, '17495', 'Norcasia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(334, 6, '17513', 'Pacora', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(335, 6, '17524', 'Palestina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(336, 6, '17541', 'Pensilvania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(337, 6, '17614', 'Riosucio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(338, 6, '17616', 'Risaralda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(339, 6, '17653', 'Salamina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(340, 6, '17662', 'Samana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(341, 6, '17665', 'San Jose', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(342, 6, '17777', 'Supia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(343, 6, '17867', 'Victoria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(344, 6, '17873', 'Villamaria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(345, 6, '17877', 'Viterbo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(346, 7, '18001', 'Florencia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(347, 7, '18029', 'Albania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(348, 7, '18094', 'Belen se los Andaquies', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(349, 7, '18150', 'Cartagena del Chaira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(350, 7, '18205', 'Curillo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(351, 7, '18247', 'El Doncello', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(352, 7, '18256', 'El Paujil', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(353, 7, '18410', 'La Montañita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(354, 7, '18460', 'Milan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(355, 7, '18479', 'Morelia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(356, 7, '18592', 'Puerto Rico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(357, 7, '18610', 'San Jose del Fragua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(358, 7, '18753', 'San Vicente del Caguan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(359, 7, '18756', 'Solano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(360, 7, '18785', 'Solita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(361, 7, '18860', 'Valparaiso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(362, 8, '19001', 'Popayan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(363, 8, '19022', 'Almaguer', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(364, 8, '19050', 'Argelia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(365, 8, '19075', 'Balboa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(366, 8, '19100', 'Bolivar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(367, 8, '19110', 'Buenos Aires', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(368, 8, '19130', 'Cajibio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(369, 8, '19137', 'Caldono', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(370, 8, '19142', 'Caloto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(371, 8, '19212', 'Corinto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(372, 8, '19256', 'El Tambo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(373, 8, '19290', 'Florencia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(374, 8, '19300', 'Guachene', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(375, 8, '19318', 'Guapi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(376, 8, '19355', 'Inza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(377, 8, '19364', 'Jambalo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(378, 8, '19392', 'La Sierra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(379, 8, '19397', 'La Vega', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(380, 8, '19418', 'Lopez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(381, 8, '19450', 'Mercaderes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(382, 8, '19455', 'Miranda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(383, 8, '19473', 'Morales', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(384, 8, '19513', 'Padilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(385, 8, '19517', 'Paez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(386, 8, '19532', 'Patia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(387, 8, '19533', 'Piamonte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(388, 8, '19548', 'Piendamo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(389, 8, '19573', 'Puerto Tejada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(390, 8, '19585', 'Purace', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(391, 8, '19622', 'Rosas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(392, 8, '19693', 'San Sebastian', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(393, 8, '19698', 'Santander de Quilichao', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(394, 8, '19701', 'Santa Rosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(395, 8, '19743', 'Silvia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(396, 8, '19760', 'Sotara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(397, 8, '19780', 'Suarez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(398, 8, '19785', 'Sucre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(399, 8, '19807', 'Timbio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(400, 8, '19809', 'Timbiqui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(401, 8, '19821', 'Toribio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(402, 8, '19824', 'Totoro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(403, 8, '19845', 'Villa Rica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(404, 9, '20001', 'Valledupar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(405, 9, '20011', 'Aguachica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(406, 9, '20013', 'Agustin Codazzi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(407, 9, '20032', 'Astrea', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(408, 9, '20045', 'Becerril', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(409, 9, '20060', 'Bosconia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(410, 9, '20175', 'Chimichagua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(411, 9, '20178', 'Chiriguana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(412, 9, '20228', 'Curumani', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(413, 9, '20238', 'El Copey', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(414, 9, '20250', 'El Paso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(415, 9, '20295', 'Gamarra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(416, 9, '20310', 'Gonzalez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(417, 9, '20383', 'La Gloria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(418, 9, '20400', 'La Jagua De Ibirico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(419, 9, '20443', 'Manaure', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(420, 9, '20517', 'Pailitas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(421, 9, '20550', 'Pelaya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(422, 9, '20570', 'Pueblo Bello', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(423, 9, '20614', 'Rio De Oro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(424, 9, '20621', 'La Paz', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(425, 9, '20710', 'San Alberto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(426, 9, '20750', 'San Diego', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(427, 9, '20770', 'San Martin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(428, 9, '20787', 'Tamalameque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(429, 10, '23001', 'Monteria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(430, 10, '23068', 'Ayapel', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(431, 10, '23079', 'Buenavista', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(432, 10, '23090', 'Canalete', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(433, 10, '23162', 'Cerete', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(434, 10, '23168', 'Chima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(435, 10, '23182', 'Chinu', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(436, 10, '23189', 'Cienaga De Oro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(437, 10, '23300', 'Cotorra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(438, 10, '23350', 'La Apartada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(439, 10, '23417', 'Lorica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(440, 10, '23419', 'Los Cordobas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(441, 10, '23464', 'Momil', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(442, 10, '23466', 'Montelibano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(443, 10, '23500', 'Moñitos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(444, 10, '23555', 'Planeta Rica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(445, 10, '23570', 'Pueblo Nuevo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(446, 10, '23574', 'Puerto Escondido', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(447, 10, '23580', 'Puerto Libertador', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(448, 10, '23586', 'Purisima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(449, 10, '23660', 'Sahagun', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(450, 10, '23670', 'San Andres Sotavento', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(451, 10, '23672', 'San Antero', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(452, 10, '23675', 'San Bernardo Del Viento', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(453, 10, '23678', 'San Carlos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(454, 10, '23682', 'San José De Uré', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(455, 10, '23686', 'San Pelayo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(456, 10, '23807', 'Tierralta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(457, 10, '23815', 'Tuchín', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(458, 10, '23855', 'Valencia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(459, 11, '25001', 'Agua De Dios', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(460, 11, '25019', 'Alban', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(461, 11, '25035', 'Anapoima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(462, 11, '25040', 'Anolaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(463, 11, '25053', 'Arbelaez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(464, 11, '25086', 'Beltran', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(465, 11, '25095', 'Bituima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(466, 11, '25099', 'Bojaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(467, 11, '25120', 'Cabrera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(468, 11, '25123', 'Cachipay', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(469, 11, '25126', 'Cajica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(470, 11, '25148', 'Caparrapi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(471, 11, '25151', 'Caqueza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(472, 11, '25154', 'Carmen De Carupa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(473, 11, '25168', 'Chaguani', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(474, 11, '25175', 'Chia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(475, 11, '25178', 'Chipaque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(476, 11, '25181', 'Choachi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(477, 11, '25183', 'Choconta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(478, 11, '25200', 'Cogua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(479, 11, '25214', 'Cota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(480, 11, '25224', 'Cucunuba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(481, 11, '25245', 'El Colegio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(482, 11, '25258', 'El Peñon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(483, 11, '25260', 'El Rosal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(484, 11, '25269', 'Facatativa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(485, 11, '25279', 'Fomeque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(486, 11, '25281', 'Fosca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(487, 11, '25286', 'Funza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(488, 11, '25288', 'Fuquene', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(489, 11, '25290', 'Fusagasuga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(490, 11, '25293', 'Gachala', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(491, 11, '25295', 'Gachancipa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(492, 11, '25297', 'Gacheta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(493, 11, '25299', 'Gama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(494, 11, '25307', 'Girardot', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(495, 11, '25312', 'Granada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(496, 11, '25317', 'Guacheta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(497, 11, '25320', 'Guaduas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(498, 11, '25322', 'Guasca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(499, 11, '25324', 'Guataqui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(500, 11, '25326', 'Guatavita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(501, 11, '25328', 'Guayabal De Siquima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(502, 11, '25335', 'Guayabetal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(503, 11, '25339', 'Gutierrez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(504, 11, '25368', 'Jerusalen', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(505, 11, '25372', 'Junin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(506, 11, '25377', 'La Calera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(507, 11, '25386', 'La Mesa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(508, 11, '25394', 'La Palma', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(509, 11, '25398', 'La Peña', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(510, 11, '25402', 'La Vega', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(511, 11, '25407', 'Lenguazaque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(512, 11, '25426', 'Macheta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(513, 11, '25430', 'Madrid', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(514, 11, '25436', 'Manta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(515, 11, '25438', 'Medina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(516, 11, '25473', 'Mosquera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(517, 11, '25483', 'Nariño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(518, 11, '25486', 'Nemocon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(519, 11, '25488', 'Nilo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(520, 11, '25489', 'Nimaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(521, 11, '25491', 'Nocaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(522, 11, '25506', 'Venecia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(523, 11, '25513', 'Pacho', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(524, 11, '25518', 'Paime', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(525, 11, '25524', 'Pandi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(526, 11, '25530', 'Paratebueno', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(527, 11, '25535', 'Pasca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(528, 11, '25572', 'Puerto Salgar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(529, 11, '25580', 'Puli', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(530, 11, '25592', 'Quebradanegra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(531, 11, '25594', 'Quetame', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(532, 11, '25596', 'Quipile', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(533, 11, '25599', 'Apulo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(534, 11, '25612', 'Ricaurte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(535, 11, '25645', 'San Antonio Del Tequendama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(536, 11, '25649', 'San Bernardo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(537, 11, '25653', 'San Cayetano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(538, 11, '25658', 'San Francisco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(539, 11, '25662', 'San Juan De Rio Seco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(540, 11, '25718', 'Sasaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(541, 11, '25736', 'Sesquile', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(542, 11, '25740', 'Sibate', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(543, 11, '25743', 'Silvania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(544, 11, '25745', 'Simijaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(545, 11, '25754', 'Soacha', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(546, 11, '25758', 'Sopo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(547, 11, '25769', 'Subachoque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(548, 11, '25772', 'Suesca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(549, 11, '25777', 'Supata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(550, 11, '25779', 'Susa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(551, 11, '25781', 'Sutatausa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(552, 11, '25785', 'Tabio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(553, 11, '25793', 'Tausa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(554, 11, '25797', 'Tena', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(555, 11, '25799', 'Tenjo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(556, 11, '25805', 'Tibacuy', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(557, 11, '25807', 'Tibirita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(558, 11, '25815', 'Tocaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(559, 11, '25817', 'Tocancipa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(560, 11, '25823', 'Topaipi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(561, 11, '25839', 'Ubala', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(562, 11, '25841', 'Ubaque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(563, 11, '25843', 'Villa De San Diego De Ubate', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(564, 11, '25845', 'Une', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(565, 11, '25851', 'Utica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(566, 11, '25862', 'Vergara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(567, 11, '25867', 'Viani', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(568, 11, '25871', 'Villagomez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(569, 11, '25873', 'Villapinzon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(570, 11, '25875', 'Villeta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(571, 11, '25878', 'Viota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(572, 11, '25885', 'Yacopi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(573, 11, '25898', 'Zipacon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(574, 11, '25899', 'Zipaquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(575, 12, '27001', 'Quibdo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(576, 12, '27006', 'Acandi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(577, 12, '27025', 'Alto Baudo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(578, 12, '27050', 'Atrato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(579, 12, '27073', 'Bagado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(580, 12, '27075', 'Bahia Solano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(581, 12, '27077', 'Bajo Baudo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(582, 12, '27099', 'Bojaya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(583, 12, '27135', 'El Canton Del San Pablo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(584, 12, '27150', 'Carmen Del Darien', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(585, 12, '27160', 'Certegui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(586, 12, '27205', 'Condoto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(587, 12, '27245', 'El Carmen De Atrato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(588, 12, '27250', 'El Litoral Del San Juan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(589, 12, '27361', 'Istmina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(590, 12, '27372', 'Jurado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(591, 12, '27413', 'Lloro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(592, 12, '27425', 'Medio Atrato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(593, 12, '27430', 'Medio Baudo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(594, 12, '27450', 'Medio San Juan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(595, 12, '27491', 'Novita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(596, 12, '27495', 'Nuqui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(597, 12, '27580', 'Rio Iro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(598, 12, '27600', 'Rio Quito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(599, 12, '27615', 'Riosucio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(600, 12, '27660', 'San Jose Del Palmar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(601, 12, '27745', 'Sipi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(602, 12, '27787', 'Tado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(603, 12, '27800', 'Unguia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(604, 12, '27810', 'Union Panamericana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(605, 13, '41001', 'Neiva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(606, 13, '41006', 'Acevedo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(607, 13, '41013', 'Agrado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(608, 13, '41016', 'Aipe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(609, 13, '41020', 'Algeciras', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(610, 13, '41026', 'Altamira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(611, 13, '41078', 'Baraya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(612, 13, '41132', 'Campoalegre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(613, 13, '41206', 'Colombia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(614, 13, '41244', 'Elias', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(615, 13, '41298', 'Garzon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(616, 13, '41306', 'Gigante', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(617, 13, '41319', 'Guadalupe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(618, 13, '41349', 'Hobo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(619, 13, '41357', 'Iquira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(620, 13, '41359', 'Isnos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(621, 13, '41378', 'La Argentina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(622, 13, '41396', 'La Plata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(623, 13, '41483', 'Nataga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(624, 13, '41503', 'Oporapa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(625, 13, '41518', 'Paicol', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(626, 13, '41524', 'Palermo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(627, 13, '41530', 'Palestina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(628, 13, '41548', 'Pital', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(629, 13, '41551', 'Pitalito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(630, 13, '41615', 'Rivera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(631, 13, '41660', 'Saladoblanco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(632, 13, '41668', 'San Agustin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41');
INSERT INTO `municipio` (`muniid`, `munidepaid`, `municodigo`, `muninombre`, `munihacepresencia`, `created_at`, `updated_at`) VALUES
(633, 13, '41676', 'Santa Maria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(634, 13, '41770', 'Suaza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(635, 13, '41791', 'Tarqui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(636, 13, '41797', 'Tesalia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(637, 13, '41799', 'Tello', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(638, 13, '41801', 'Teruel', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(639, 13, '41807', 'Timana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(640, 13, '41872', 'Villavieja', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(641, 13, '41885', 'Yaguara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(642, 14, '44001', 'Riohacha', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(643, 14, '44035', 'Albania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(644, 14, '44078', 'Barrancas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(645, 14, '44090', 'Dibulla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(646, 14, '44098', 'Distraccion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(647, 14, '44110', 'El Molino', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(648, 14, '44279', 'Fonseca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(649, 14, '44378', 'Hatonuevo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(650, 14, '44420', 'La Jagua Del Pilar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(651, 14, '44430', 'Maicao', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(652, 14, '44560', 'Manaure', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(653, 14, '44650', 'San Juan Del Cesar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(654, 14, '44847', 'Uribia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(655, 14, '44855', 'Urumita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(656, 14, '44874', 'Villanueva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(657, 15, '47001', 'Santa Marta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(658, 15, '47030', 'Algarrobo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(659, 15, '47053', 'Aracataca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(660, 15, '47058', 'Ariguani', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(661, 15, '47161', 'Cerro San Antonio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(662, 15, '47170', 'Chibolo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(663, 15, '47189', 'Cienaga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(664, 15, '47205', 'Concordia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(665, 15, '47245', 'El Banco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(666, 15, '47258', 'El Piñon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(667, 15, '47268', 'El Reten', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(668, 15, '47288', 'Fundacion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(669, 15, '47318', 'Guamal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(670, 15, '47460', 'Nueva Granada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(671, 15, '47541', 'Pedraza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(672, 15, '47545', 'Pijiño Del Carmen', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(673, 15, '47551', 'Pivijay', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(674, 15, '47555', 'Plato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(675, 15, '47570', 'Puebloviejo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(676, 15, '47605', 'Remolino', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(677, 15, '47660', 'Sabanas De San Angel', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(678, 15, '47675', 'Salamina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(679, 15, '47692', 'San Sebastian De Buenavista', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(680, 15, '47703', 'San Zenon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(681, 15, '47707', 'Santa Ana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(682, 15, '47720', 'Santa Barbara De Pinto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(683, 15, '47745', 'Sitionuevo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(684, 15, '47798', 'Tenerife', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(685, 15, '47960', 'Zapayan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(686, 15, '47980', 'Zona Bananera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(687, 16, '50001', 'Villavicencio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(688, 16, '50006', 'Acacias', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(689, 16, '50110', 'Barranca De Upia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(690, 16, '50124', 'Cabuyaro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(691, 16, '50150', 'Castilla La Nueva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(692, 16, '50223', 'Cubarral', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(693, 16, '50226', 'Cumaral', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(694, 16, '50245', 'El Calvario', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(695, 16, '50251', 'El Castillo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(696, 16, '50270', 'El Dorado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(697, 16, '50287', 'Fuente De Oro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(698, 16, '50313', 'Granada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(699, 16, '50318', 'Guamal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(700, 16, '50325', 'Mapiripan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(701, 16, '50330', 'Mesetas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(702, 16, '50350', 'La Macarena', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(703, 16, '50370', 'Uribe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(704, 16, '50400', 'Lejanias', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(705, 16, '50450', 'Puerto Concordia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(706, 16, '50568', 'Puerto Gaitan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(707, 16, '50573', 'Puerto Lopez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(708, 16, '50577', 'Puerto Lleras', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(709, 16, '50590', 'Puerto Rico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(710, 16, '50606', 'Restrepo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(711, 16, '50680', 'San Carlos De Guaroa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(712, 16, '50683', 'San Juan De Arama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(713, 16, '50686', 'San Juanito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(714, 16, '50689', 'San Martin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(715, 16, '50711', 'Vistahermosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(716, 17, '52001', 'Pasto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(717, 17, '52019', 'Alban', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(718, 17, '52022', 'Aldana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(719, 17, '52036', 'Ancuya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(720, 17, '52051', 'Arboleda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(721, 17, '52079', 'Barbacoas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(722, 17, '52083', 'Belen', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(723, 17, '52110', 'Buesaco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(724, 17, '52203', 'Colon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(725, 17, '52207', 'Consaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(726, 17, '52210', 'Contadero', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(727, 17, '52215', 'Cordoba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(728, 17, '52224', 'Cuaspud', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(729, 17, '52227', 'Cumbal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(730, 17, '52233', 'Cumbitara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(731, 17, '52240', 'Chachagsi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(732, 17, '52250', 'El Charco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(733, 17, '52254', 'El Peñol', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(734, 17, '52256', 'El Rosario', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(735, 17, '52258', 'El Tablon De Gomez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(736, 17, '52260', 'El Tambo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(737, 17, '52287', 'Funes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(738, 17, '52317', 'Guachucal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(739, 17, '52320', 'Guaitarilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(740, 17, '52323', 'Gualmatan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(741, 17, '52352', 'Iles', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(742, 17, '52354', 'Imues', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(743, 17, '52356', 'Ipiales', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(744, 17, '52378', 'La Cruz', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(745, 17, '52381', 'La Florida', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(746, 17, '52385', 'La Llanada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(747, 17, '52390', 'La Tola', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(748, 17, '52399', 'La Union', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(749, 17, '52405', 'Leiva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(750, 17, '52411', 'Linares', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(751, 17, '52418', 'Los Andes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(752, 17, '52427', 'Magsi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(753, 17, '52435', 'Mallama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(754, 17, '52473', 'Mosquera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(755, 17, '52480', 'Nariño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(756, 17, '52490', 'Olaya Herrera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(757, 17, '52506', 'Ospina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(758, 17, '52520', 'Francisco Pizarro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(759, 17, '52540', 'Policarpa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(760, 17, '52560', 'Potosi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(761, 17, '52565', 'Providencia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(762, 17, '52573', 'Puerres', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(763, 17, '52585', 'Pupiales', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(764, 17, '52612', 'Ricaurte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(765, 17, '52621', 'Roberto Payan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(766, 17, '52678', 'Samaniego', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(767, 17, '52683', 'Sandona', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(768, 17, '52685', 'San Bernardo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(769, 17, '52687', 'San Lorenzo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(770, 17, '52693', 'San Pablo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(771, 17, '52694', 'San Pedro De Cartago', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(772, 17, '52696', 'Santa Barbara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(773, 17, '52699', 'Santacruz', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(774, 17, '52720', 'Sapuyes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(775, 17, '52786', 'Taminango', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(776, 17, '52788', 'Tangua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(777, 17, '52835', 'San Andres De Tumaco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(778, 17, '52838', 'Tuquerres', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(779, 17, '52885', 'Yacuanquer', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(780, 18, '54001', 'Cucuta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(781, 18, '54003', 'Abrego', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(782, 18, '54051', 'Arboledas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(783, 18, '54099', 'Bochalema', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(784, 18, '54109', 'Bucarasica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(785, 18, '54125', 'Cacota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(786, 18, '54128', 'Cachira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(787, 18, '54172', 'Chinacota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(788, 18, '54174', 'Chitaga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(789, 18, '54206', 'Convencion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(790, 18, '54223', 'Cucutilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(791, 18, '54239', 'Durania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(792, 18, '54245', 'El Carmen', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(793, 18, '54250', 'El Tarra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(794, 18, '54261', 'El Zulia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(795, 18, '54313', 'Gramalote', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(796, 18, '54344', 'Hacari', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(797, 18, '54347', 'Herran', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(798, 18, '54377', 'Labateca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(799, 18, '54385', 'La Esperanza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(800, 18, '54398', 'La Playa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(801, 18, '54405', 'Los Patios', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(802, 18, '54418', 'Lourdes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(803, 18, '54480', 'Mutiscua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(804, 18, '54498', 'Ocaña', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(805, 18, '54518', 'Pamplona', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(806, 18, '54520', 'Pamplonita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(807, 18, '54553', 'Puerto Santander', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(808, 18, '54599', 'Ragonvalia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(809, 18, '54660', 'Salazar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(810, 18, '54670', 'San Calixto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(811, 18, '54673', 'San Cayetano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(812, 18, '54680', 'Santiago', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(813, 18, '54720', 'Sardinata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(814, 18, '54743', 'Silos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(815, 18, '54800', 'Teorama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(816, 18, '54810', 'Tibu', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(817, 18, '54820', 'Toledo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(818, 18, '54871', 'Villa Caro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(819, 18, '54874', 'Villa Del Rosario', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(820, 19, '63001', 'Armenia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(821, 19, '63111', 'Buenavista', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(822, 19, '63130', 'Calarca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(823, 19, '63190', 'Circasia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(824, 19, '63212', 'Cordoba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(825, 19, '63272', 'Filandia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(826, 19, '63302', 'Genova', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(827, 19, '63401', 'La Tebaida', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(828, 19, '63470', 'Montenegro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(829, 19, '63548', 'Pijao', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(830, 19, '63594', 'Quimbaya', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(831, 19, '63690', 'Salento', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(832, 20, '66001', 'Pereira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(833, 20, '66045', 'Apia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(834, 20, '66075', 'Balboa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(835, 20, '66088', 'Belen De Umbria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(836, 20, '66170', 'Dosquebradas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(837, 20, '66318', 'Guatica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(838, 20, '66383', 'La Celia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(839, 20, '66400', 'La Virginia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(840, 20, '66440', 'Marsella', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(841, 20, '66456', 'Mistrato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(842, 20, '66572', 'Pueblo Rico', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(843, 20, '66594', 'Quinchia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(844, 20, '66682', 'Santa Rosa De Cabal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(845, 20, '66687', 'Santuario', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(846, 21, '68001', 'Bucaramanga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(847, 21, '68013', 'Aguada', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(848, 21, '68020', 'Albania', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(849, 21, '68051', 'Aratoca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(850, 21, '68077', 'Barbosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(851, 21, '68079', 'Barichara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(852, 21, '68081', 'Barrancabermeja', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(853, 21, '68092', 'Betulia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(854, 21, '68101', 'Bolivar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(855, 21, '68121', 'Cabrera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(856, 21, '68132', 'California', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(857, 21, '68147', 'Capitanejo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(858, 21, '68152', 'Carcasi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(859, 21, '68160', 'Cepita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(860, 21, '68162', 'Cerrito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(861, 21, '68167', 'Charala', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(862, 21, '68169', 'Charta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(863, 21, '68176', 'Chima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(864, 21, '68179', 'Chipata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(865, 21, '68190', 'Cimitarra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(866, 21, '68207', 'Concepcion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(867, 21, '68209', 'Confines', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(868, 21, '68211', 'Contratacion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(869, 21, '68217', 'Coromoro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(870, 21, '68229', 'Curiti', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(871, 21, '68235', 'El Carmen De Chucuri', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(872, 21, '68245', 'El Guacamayo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(873, 21, '68250', 'El Peñon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(874, 21, '68255', 'El Playon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(875, 21, '68264', 'Encino', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(876, 21, '68266', 'Enciso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(877, 21, '68271', 'Florian', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(878, 21, '68276', 'Floridablanca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(879, 21, '68296', 'Galan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(880, 21, '68298', 'Gambita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(881, 21, '68307', 'Giron', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(882, 21, '68318', 'Guaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(883, 21, '68320', 'Guadalupe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(884, 21, '68322', 'Guapota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(885, 21, '68324', 'Guavata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(886, 21, '68327', 'Gsepsa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(887, 21, '68344', 'Hato', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(888, 21, '68368', 'Jesus Maria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(889, 21, '68370', 'Jordan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(890, 21, '68377', 'La Belleza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(891, 21, '68385', 'Landazuri', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(892, 21, '68397', 'La Paz', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(893, 21, '68406', 'Lebrija', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(894, 21, '68418', 'Los Santos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(895, 21, '68425', 'Macaravita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(896, 21, '68432', 'Malaga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(897, 21, '68444', 'Matanza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(898, 21, '68464', 'Mogotes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(899, 21, '68468', 'Molagavita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(900, 21, '68498', 'Ocamonte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(901, 21, '68500', 'Oiba', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(902, 21, '68502', 'Onzaga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(903, 21, '68522', 'Palmar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(904, 21, '68524', 'Palmas Del Socorro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(905, 21, '68533', 'Paramo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(906, 21, '68547', 'Piedecuesta', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(907, 21, '68549', 'Pinchote', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(908, 21, '68572', 'Puente Nacional', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(909, 21, '68573', 'Puerto Parra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(910, 21, '68575', 'Puerto Wilches', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(911, 21, '68615', 'Rionegro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(912, 21, '68655', 'Sabana De Torres', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(913, 21, '68669', 'San Andres', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(914, 21, '68673', 'San Benito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(915, 21, '68679', 'San Gil', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(916, 21, '68682', 'San Joaquin', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(917, 21, '68684', 'San Jose De Miranda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(918, 21, '68686', 'San Miguel', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(919, 21, '68689', 'San Vicente De Chucuri', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(920, 21, '68705', 'Santa Barbara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(921, 21, '68720', 'Santa Helena Del Opon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(922, 21, '68745', 'Simacota', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(923, 21, '68755', 'Socorro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(924, 21, '68770', 'Suaita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(925, 21, '68773', 'Sucre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(926, 21, '68780', 'Surata', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(927, 21, '68820', 'Tona', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(928, 21, '68855', 'Valle De San Jose', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(929, 21, '68861', 'Velez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(930, 21, '68867', 'Vetas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(931, 21, '68872', 'Villanueva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(932, 21, '68895', 'Zapatoca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(933, 22, '70001', 'Sincelejo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(934, 22, '70110', 'Buenavista', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(935, 22, '70124', 'Caimito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(936, 22, '70204', 'Coloso', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(937, 22, '70215', 'Corozal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(938, 22, '70221', 'Coveñas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(939, 22, '70230', 'Chalan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(940, 22, '70233', 'El Roble', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(941, 22, '70235', 'Galeras', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(942, 22, '70265', 'Guaranda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(943, 22, '70400', 'La Union', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(944, 22, '70418', 'Los Palmitos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(945, 22, '70429', 'Majagual', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(946, 22, '70473', 'Morroa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(947, 22, '70508', 'Ovejas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(948, 22, '70523', 'Palmito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(949, 22, '70670', 'Sampues', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(950, 22, '70678', 'San Benito Abad', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(951, 22, '70702', 'San Juan De Betulia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(952, 22, '70708', 'San Marcos', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(953, 22, '70713', 'San Onofre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(954, 22, '70717', 'San Pedro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(955, 22, '70742', 'San Luis De Since', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(956, 22, '70771', 'Sucre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(957, 22, '70820', 'Santiago De Tolu', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(958, 22, '70823', 'Tolu Viejo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(959, 23, '73001', 'Ibague', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(960, 23, '73024', 'Alpujarra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(961, 23, '73026', 'Alvarado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(962, 23, '73030', 'Ambalema', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(963, 23, '73043', 'Anzoategui', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(964, 23, '73055', 'Armero', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(965, 23, '73067', 'Ataco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(966, 23, '73124', 'Cajamarca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(967, 23, '73148', 'Carmen De Apicala', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(968, 23, '73152', 'Casabianca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(969, 23, '73168', 'Chaparral', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(970, 23, '73200', 'Coello', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(971, 23, '73217', 'Coyaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(972, 23, '73226', 'Cunday', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(973, 23, '73236', 'Dolores', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(974, 23, '73268', 'Espinal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(975, 23, '73270', 'Falan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(976, 23, '73275', 'Flandes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(977, 23, '73283', 'Fresno', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(978, 23, '73319', 'Guamo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(979, 23, '73347', 'Herveo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(980, 23, '73349', 'Honda', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(981, 23, '73352', 'Icononzo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(982, 23, '73408', 'Lerida', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(983, 23, '73411', 'Libano', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(984, 23, '73443', 'Mariquita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(985, 23, '73449', 'Melgar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(986, 23, '73461', 'Murillo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(987, 23, '73483', 'Natagaima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(988, 23, '73504', 'Ortega', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(989, 23, '73520', 'Palocabildo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(990, 23, '73547', 'Piedras', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(991, 23, '73555', 'Planadas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(992, 23, '73563', 'Prado', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(993, 23, '73585', 'Purificacion', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(994, 23, '73616', 'Rioblanco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(995, 23, '73622', 'Roncesvalles', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(996, 23, '73624', 'Rovira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(997, 23, '73671', 'Saldaña', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(998, 23, '73675', 'San Antonio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(999, 23, '73678', 'San Luis', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1000, 23, '73686', 'Santa Isabel', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1001, 23, '73770', 'Suarez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1002, 23, '73854', 'Valle De San Juan', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1003, 23, '73861', 'Venadillo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1004, 23, '73870', 'Villahermosa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1005, 23, '73873', 'Villarrica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1006, 24, '76001', 'Cali', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1007, 24, '76020', 'Alcala', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1008, 24, '76036', 'Andalucia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1009, 24, '76041', 'Ansermanuevo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1010, 24, '76054', 'Argelia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1011, 24, '76100', 'Bolivar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1012, 24, '76109', 'Buenaventura', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1013, 24, '76111', 'Guadalajara De Buga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1014, 24, '76113', 'Bugalagrande', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1015, 24, '76122', 'Caicedonia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1016, 24, '76126', 'Calima', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1017, 24, '76130', 'Candelaria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1018, 24, '76147', 'Cartago', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1019, 24, '76233', 'Dagua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1020, 24, '76243', 'El Aguila', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1021, 24, '76246', 'El Cairo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1022, 24, '76248', 'El Cerrito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1023, 24, '76250', 'El Dovio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1024, 24, '76275', 'Florida', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1025, 24, '76306', 'Ginebra', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1026, 24, '76318', 'Guacari', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1027, 24, '76364', 'Jamundi', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1028, 24, '76377', 'La Cumbre', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1029, 24, '76400', 'La Union', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1030, 24, '76403', 'La Victoria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1031, 24, '76497', 'Obando', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1032, 24, '76520', 'Palmira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1033, 24, '76563', 'Pradera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1034, 24, '76606', 'Restrepo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1035, 24, '76616', 'Riofrio', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1036, 24, '76622', 'Roldanillo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1037, 24, '76670', 'San Pedro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1038, 24, '76736', 'Sevilla', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1039, 24, '76823', 'Toro', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1040, 24, '76828', 'Trujillo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1041, 24, '76834', 'Tulua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1042, 24, '76845', 'Ulloa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1043, 24, '76863', 'Versalles', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1044, 24, '76869', 'Vijes', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1045, 24, '76890', 'Yotoco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1046, 24, '76892', 'Yumbo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1047, 24, '76895', 'Zarzal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1048, 25, '81001', 'Arauca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1049, 25, '81065', 'Arauquita', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1050, 25, '81220', 'Cravo Norte', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1051, 25, '81300', 'Fortul', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1052, 25, '81591', 'Puerto Rondon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1053, 25, '81736', 'Saravena', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1054, 25, '81794', 'Tame', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1055, 26, '85001', 'Yopal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1056, 26, '85010', 'Aguazul', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1057, 26, '85015', 'Chameza', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1058, 26, '85125', 'Hato Corozal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1059, 26, '85136', 'La Salina', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1060, 26, '85139', 'Mani', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1061, 26, '85162', 'Monterrey', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1062, 26, '85225', 'Nunchia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1063, 26, '85230', 'Orocue', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1064, 26, '85250', 'Paz De Ariporo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1065, 26, '85263', 'Pore', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1066, 26, '85279', 'Recetor', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1067, 26, '85300', 'Sabanalarga', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1068, 26, '85315', 'Sacama', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1069, 26, '85325', 'San Luis De Palenque', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1070, 26, '85400', 'Tamara', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1071, 26, '85410', 'Tauramena', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1072, 26, '85430', 'Trinidad', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1073, 26, '85440', 'Villanueva', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1074, 27, '86001', 'Mocoa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1075, 27, '86219', 'Colon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1076, 27, '86320', 'Orito', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1077, 27, '86568', 'Puerto Asis', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1078, 27, '86569', 'Puerto Caicedo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1079, 27, '86571', 'Puerto Guzman', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1080, 27, '86573', 'Leguizamo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1081, 27, '86749', 'Sibundoy', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1082, 27, '86755', 'San Francisco', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1083, 27, '86757', 'San Miguel', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1084, 27, '86760', 'Santiago', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1085, 27, '86865', 'Valle Del Guamuez', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1086, 27, '86885', 'Villagarzon', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1087, 28, '88001', 'San Andres', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1088, 28, '88564', 'Providencia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1089, 29, '91001', 'Leticia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1090, 29, '91263', 'El Encanto', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1091, 29, '91405', 'La Chorrera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1092, 29, '91407', 'La Pedrera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1093, 29, '91430', 'La Victoria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1094, 29, '91460', 'Miriti - Parana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1095, 29, '91530', 'Puerto Alegria', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1096, 29, '91536', 'Puerto Arica', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1097, 29, '91540', 'Puerto Nariño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1098, 29, '91669', 'Puerto Santander', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1099, 29, '91798', 'Tarapaca', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1100, 30, '94001', 'Inirida', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1101, 30, '94343', 'Barranco Minas', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1102, 30, '94663', 'Mapiripana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1103, 30, '94883', 'San Felipe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1104, 30, '94884', 'Puerto Colombia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1105, 30, '94885', 'La Guadalupe', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1106, 30, '94886', 'Cacahual', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1107, 30, '94887', 'Pana Pana', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1108, 30, '94888', 'Morichal', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1109, 31, '95001', 'San Jose Del Guaviare', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1110, 31, '95015', 'Calamar', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1111, 31, '95025', 'El Retorno', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1112, 31, '95200', 'Miraflores', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1113, 32, '97001', 'Mitu', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1114, 32, '97161', 'Caruru', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1115, 32, '97511', 'Pacoa', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1116, 32, '97666', 'Taraira', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1117, 32, '97777', 'Papunaua', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1118, 32, '97889', 'Yavarate', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1119, 33, '99001', 'Puerto Carreño', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1120, 33, '99524', 'La Primavera', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1121, 33, '99624', 'Santa Rosalia', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41'),
(1122, 33, '99773', 'Cumaribo', 0, '2023-11-09 09:29:41', '2023-11-09 09:29:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona',
  `carlabid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del cargo laboral',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `tipperid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de persona',
  `persdepaidnacimiento` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de nacimiento del documento',
  `persmuniidnacimiento` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de nacimiento del documento',
  `persdepaidexpedicion` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de expedición del documento',
  `persmuniidexpedicion` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de expedición del documento',
  `persdocumento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de documento de la persona',
  `persprimernombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Primer nombre de la persona',
  `perssegundonombre` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Segundo nombre de la persona',
  `persprimerapellido` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Primer apellido de la persona',
  `perssegundoapellido` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Segundo apellido de la persona',
  `persfechanacimiento` date DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `persdireccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Determina el genero de la persona',
  `perscorreoelectronico` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo electrónico de la persona',
  `persfechadexpedicion` date DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `persnumerotelefonofijo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona',
  `persnumerocelular` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona',
  `persgenero` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Determina el genero de la persona',
  `persrutafoto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la foto de la persona',
  `persrutafirma` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la firma digital de la persona para la gestión documental',
  `perstienefirmadigital` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la persona tiene firma digital',
  `persclavecertificado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clave del certificado digital',
  `persrutacrt` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de certificado digital con extensión crt',
  `persrutapem` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de certificado digital con extensión pem',
  `persactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la persona se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`persid`, `carlabid`, `tipideid`, `tipperid`, `persdepaidnacimiento`, `persmuniidnacimiento`, `persdepaidexpedicion`, `persmuniidexpedicion`, `persdocumento`, `persprimernombre`, `perssegundonombre`, `persprimerapellido`, `perssegundoapellido`, `persfechanacimiento`, `persdireccion`, `perscorreoelectronico`, `persfechadexpedicion`, `persnumerotelefonofijo`, `persnumerocelular`, `persgenero`, `persrutafoto`, `persrutafirma`, `perstienefirmadigital`, `persclavecertificado`, `persrutacrt`, `persrutapem`, `persactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'E', 18, 789, 18, 804, '1978917', 'RAMÓN', 'DAVID', 'SALAZAR', 'RINCÓN', '1979-08-29', 'Calle 4 36 49', 'radasa10@hotmail.com', '1998-04-16', '3204018506', '3204018506', 'M', NULL, 'Firma_1978917.png', 1, '123456', NULL, NULL, 1, '2023-11-09 09:30:31', '2023-11-09 09:30:31'),
(2, 4, 1, 'E', 9, 416, 9, 416, '5036123', 'LUIS', 'MANUEL', 'ASCANIO', 'CLARO', '1979-08-29', 'Calle 4 36 49', 'luisangel330@hotmail.com', '1998-04-16', '3163374329', '3163374329', 'M', NULL, NULL, 0, NULL, NULL, NULL, 1, '2023-11-09 09:30:31', '2023-11-09 09:30:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaradicadocumento`
--

CREATE TABLE `personaradicadocumento` (
  `peradoid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona radica documento',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `peradodocumento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de documento de la persona',
  `peradoprimernombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradosegundonombre` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradoprimerapellido` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradosegundoapellido` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradodireccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dirección de la persona que radica el documento',
  `peradotelefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Telefóno de la persona que radica el documento',
  `peradocorreo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'correo de la persona que radica el documento',
  `peradocodigodocumental` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código documental proveniente de la emprea que emite el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentanexo`
--

CREATE TABLE `radicaciondocentanexo` (
  `radoeaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `radoeanombreanexooriginal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento',
  `radoeanombreanexoeditado` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento pero editado',
  `radoearutaanexo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta enfuscada del anexo del radicado',
  `radoearequiereradicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el adjunto requiere radicado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentcambioestado`
--

CREATE TABLE `radicaciondocentcambioestado` (
  `radeceid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante cambio estado',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de estado radicación documento entrante',
  `radeceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del radicado',
  `radecefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del radicado',
  `radeceobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado radicado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentdependencia`
--

CREATE TABLE `radicaciondocentdependencia` (
  `radoedid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `radoedsuaid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del usuario que recibe el documento radicado',
  `radoedfechahorarecibido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se recibe el documento',
  `radoedescopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado es una copia para una dependencia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocumentoentrante`
--

CREATE TABLE `radicaciondocumentoentrante` (
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante',
  `peradoid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que radica el documento',
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de medio',
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de estado radicación documento entrante',
  `depaid` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento del cual proviene el documento',
  `muniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio del cual proviene el documento',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del documento',
  `radoenconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo del radicado',
  `radoenanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea el radicado',
  `radoenfechahoraradicado` datetime NOT NULL COMMENT 'Fecha y hora en la cual se radica el documento',
  `radoenfechamaximarespuesta` date NOT NULL COMMENT 'Fecha máxima para emitir la respuesta del radicado del documento',
  `radoenfechadocumento` date NOT NULL COMMENT 'Fecha que contiene el documento',
  `radoenfechallegada` date NOT NULL COMMENT 'Fecha de llegada del documento',
  `radoenpersonaentregadocumento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la persona que radica el documento',
  `radoenasunto` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto que contiene el documento para radicar',
  `radoentieneanexo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado tiene anexo',
  `radoendescripcionanexo` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Descripción del anexo',
  `radoentienecopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado tiene copia',
  `radoenobservacion` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación general del radicado del documento',
  `radoenrequiererespuesta` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado requiere una respuesta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla rol',
  `rolnombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del rol',
  `rolactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el rol se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rolid`, `rolnombre`, `rolactivo`, `created_at`, `updated_at`) VALUES
(1, 'Super administrador', 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(2, 'Administrador', 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(3, 'Secretaria', 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(4, 'Jefe', 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(5, 'Radicador', 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23'),
(6, 'Coordinador del archivo histórico', 1, '2023-11-09 09:31:23', '2023-11-09 09:31:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolfuncionalidad`
--

CREATE TABLE `rolfuncionalidad` (
  `rolfunid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla rol funcionalidad',
  `rolfunrolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del rol',
  `rolfunfuncid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la funcionalidad'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rolfuncionalidad`
--

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 1, 30),
(31, 1, 31),
(32, 1, 32),
(33, 1, 33),
(34, 1, 34),
(35, 1, 35),
(36, 1, 36),
(37, 1, 37),
(38, 1, 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriedocumental`
--

CREATE TABLE `seriedocumental` (
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla serie documental',
  `serdoccodigo` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de la serie',
  `serdocnombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la serie',
  `serdoctiempoarchivogestion` smallint(6) NOT NULL COMMENT 'Tiempo en el archivo de gestión',
  `serdoctiempoarchivocentral` smallint(6) NOT NULL COMMENT 'Tiempo en el archivo central',
  `serdoctiempoarchivohistorico` smallint(6) NOT NULL COMMENT 'Tiempo en el archivo histórico',
  `serdocpermiteeliminar` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la serie se puede eliminar',
  `serdocactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la serie esta activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seriedocumental`
--

INSERT INTO `seriedocumental` (`serdocid`, `serdoccodigo`, `serdocnombre`, `serdoctiempoarchivogestion`, `serdoctiempoarchivocentral`, `serdoctiempoarchivohistorico`, `serdocpermiteeliminar`, `serdocactiva`, `created_at`, `updated_at`) VALUES
(1, '001', 'Acta', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(2, '002', 'Certificado', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(3, '003', 'Circular', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(4, '004', 'Citación', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(5, '005', 'Constancia', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(6, '006', 'Oficio', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(7, '007', 'Resolucion', 360, 720, 1440, 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudcredito`
--

CREATE TABLE `solicitudcredito` (
  `solcreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla solicitud crédito',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea la solicitud de crédito',
  `lincreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la línea de crédito',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `tiesscid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado de la solicitud de crédito',
  `solcrefechasolicitud` date NOT NULL COMMENT 'Fecha de registro de la solicitud de crédito',
  `solcredescripcion` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Descripción de la solicitud de crédito',
  `solcrevalorsolicitado` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Monto o valor de la solicitud de crédito',
  `solcretasa` decimal(6,2) NOT NULL COMMENT 'Tasa de interés para solicitud de crédito',
  `solcrenumerocuota` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de cuota de la solicitud de crédito',
  `solcreobservacion` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación general de la  solicitud de crédito',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudcreditocambioestado`
--

CREATE TABLE `solicitudcreditocambioestado` (
  `socrceid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla solicitud de credito cambio estado',
  `solcreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la solicitud de crédito',
  `tiesscid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado solicitud de crédito',
  `socrceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado de la solicitud de crédito',
  `socrcefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado de la solicitud de crédito',
  `socrceobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado de la solicitud de crédito',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subseriedocumental`
--

CREATE TABLE `subseriedocumental` (
  `susedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla sub serie documental',
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla serie documental',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo documento',
  `susedocodigo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de la sub serie documental',
  `susedonombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la sub serie documental',
  `susedopermiteeliminar` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la sub serie documental se puede eliminar',
  `susedoactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la sub serie documental esta activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `subseriedocumental`
--

INSERT INTO `subseriedocumental` (`susedoid`, `serdocid`, `tipdocid`, `susedocodigo`, `susedonombre`, `susedopermiteeliminar`, `susedoactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '01', 'Acta universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(2, 2, 2, '01', 'Certificado universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(3, 3, 3, '01', 'Circular universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(4, 4, 4, '01', 'Citación universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(5, 5, 5, '01', 'Constancia universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(6, 6, 6, '01', 'Oficio universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30'),
(7, 7, 7, '01', 'Resolución universal', 0, 1, '2023-11-09 09:30:30', '2023-11-09 09:30:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoacta`
--

CREATE TABLE `tipoacta` (
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de acta',
  `tipactnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de acta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoacta`
--

INSERT INTO `tipoacta` (`tipactid`, `tipactnombre`) VALUES
(1, 'Ordinaria'),
(2, 'Extra Ordinaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocajaubicacion`
--

CREATE TABLE `tipocajaubicacion` (
  `ticaubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo caja ubicación',
  `ticaubnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de caja ubicación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocajaubicacion`
--

INSERT INTO `tipocajaubicacion` (`ticaubid`, `ticaubnombre`) VALUES
(1, 'Caja uno'),
(2, 'Caja dos'),
(3, 'Caja tres'),
(4, 'Caja cuatro'),
(5, 'Caja cinco'),
(6, 'Caja seis'),
(7, 'Caja siete'),
(8, 'Caja ocho'),
(9, 'Caja nueve'),
(10, 'Caja diez'),
(11, 'Caja once'),
(12, 'Caja doce');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocarpetaubicacion`
--

CREATE TABLE `tipocarpetaubicacion` (
  `ticrubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo carpeta ubicación',
  `ticrubnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de carpeta ubicación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocarpetaubicacion`
--

INSERT INTO `tipocarpetaubicacion` (`ticrubid`, `ticrubnombre`) VALUES
(1, 'Carpeta uno'),
(2, 'Carpeta dos'),
(3, 'Carpeta tres'),
(4, 'Carpeta cuatro'),
(5, 'Carpeta cinco'),
(6, 'Carpeta seis'),
(7, 'Carpeta siete'),
(8, 'Carpeta ocho'),
(9, 'Carpeta nueve'),
(10, 'Carpeta diez'),
(11, 'Carpeta once'),
(12, 'Carpeta doce');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocarroceriavehiculo`
--

CREATE TABLE `tipocarroceriavehiculo` (
  `ticaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo carroceria vehículo',
  `ticavenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de carroceria del vehículo',
  `ticaveactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo del carroceria del vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocarroceriavehiculo`
--

INSERT INTO `tipocarroceriavehiculo` (`ticaveid`, `ticavenombre`, `ticaveactivo`, `created_at`, `updated_at`) VALUES
(1, 'CERRADO', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(2, 'SEDÁN', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(3, 'HATCH-BACK', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(4, 'MIXTA', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(5, 'CABINADO', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(6, 'VAN', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(7, 'STAT-WAGON', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(8, 'VANS', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(9, 'CARPADO', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38'),
(10, 'ESTACAS', 1, '2023-11-09 09:30:38', '2023-11-09 09:30:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocategorialicencia`
--

CREATE TABLE `tipocategorialicencia` (
  `ticaliid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de categoría de licencia',
  `ticalinombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de categoría de la licencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocategorialicencia`
--

INSERT INTO `tipocategorialicencia` (`ticaliid`, `ticalinombre`) VALUES
('A1', 'A1'),
('A2', 'A2'),
('B1', 'B1'),
('B2', 'B2'),
('C1', 'C1'),
('C2', 'C2'),
('C3', 'C3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocolorvehiculo`
--

CREATE TABLE `tipocolorvehiculo` (
  `ticoveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo color vehículo',
  `ticovenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del color del tipo vehículo',
  `ticoveactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo del color del vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocolorvehiculo`
--

INSERT INTO `tipocolorvehiculo` (`ticoveid`, `ticovenombre`, `ticoveactivo`, `created_at`, `updated_at`) VALUES
(1, 'BLANCO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(2, 'BLANCO VERDE AMARILLO ROJO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(3, 'BLANCO VERDE AMARILLO AZUL', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(4, 'VERDE BLANCO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(5, 'BLANCO NIEVE', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(6, 'BLANCO VERDE', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(7, 'AMARILLO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(8, 'AMARILLO URBANO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(9, 'AMARILLO LIMA', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(10, 'BLANCO NIEBLA', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(11, 'BLANCO GALAXIA', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(12, 'BLANCO VERDE AMARILLO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(13, 'AMARILLO BLANCO VERDE', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(14, 'BLANCO GLACIAL', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(15, 'BLANCO AZUL ROJO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(16, 'BLANCO ÁRTICO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(17, 'AZUL', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(18, 'BLANCO POLAR', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(19, 'VERDE', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(20, 'AZUL AMARILLO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(21, 'VERDE AMARILLO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(22, 'NARANJA-CREMA', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(23, 'VERDE AMARILLO ROJO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(24, 'ROJO LADRILLO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(25, 'ROJO VERDE BLANCO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocombustiblevehiculo`
--

CREATE TABLE `tipocombustiblevehiculo` (
  `ticovhid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo combustible vehículo',
  `ticovhnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo combustible vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocombustiblevehiculo`
--

INSERT INTO `tipocombustiblevehiculo` (`ticovhid`, `ticovhnombre`) VALUES
(1, 'ACPM'),
(2, 'GASOLINA'),
(3, 'GAS'),
(4, 'HIBRIDO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoconductor`
--

CREATE TABLE `tipoconductor` (
  `tipconid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo conductor',
  `tipconnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de conductor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoconductor`
--

INSERT INTO `tipoconductor` (`tipconid`, `tipconnombre`) VALUES
('P', 'PRINCIPAL'),
('R', 'RELEVADOR'),
('S', 'SUPLENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodespedida`
--

CREATE TABLE `tipodespedida` (
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `tipdesnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo despedida',
  `tipdesactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de despedida se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodespedida`
--

INSERT INTO `tipodespedida` (`tipdesid`, `tipdesnombre`, `tipdesactivo`, `created_at`, `updated_at`) VALUES
(1, 'Atentamente,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(2, 'Atentamente le saluda,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(3, 'Atentamente se despide,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(4, 'Agradecidos por su amabilidad,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(5, 'Agradecidos por su atención,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(6, 'Cordialmente se despide,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(7, 'Sin otro particular por el momento,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(8, 'Reiteramos nuestros mas cordiales saludos,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(9, 'Nuestra consideracion mas distinguida,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(10, 'En espera de sus noticias le saludamos,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(11, 'Un atento saludo,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(12, 'Agradeciendo su valiosa colaboración,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(13, 'En espera de una respuesta,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(14, 'Quedamos a su disposicion por cuanto puedan necesitar', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(15, 'Les quedamos muy agradecidos por su colaboración', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28'),
(16, 'Hasta otra oportunidad,', 1, '2023-11-09 09:30:28', '2023-11-09 09:30:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodestino`
--

CREATE TABLE `tipodestino` (
  `tipdetid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de destino',
  `tipdetnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de destino'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodestino`
--

INSERT INTO `tipodestino` (`tipdetid`, `tipdetnombre`) VALUES
(1, 'Interno'),
(2, 'Externo'),
(3, 'Interno / Externo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumental`
--

CREATE TABLE `tipodocumental` (
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo documental',
  `tipdoccodigo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código del tipo documental',
  `tipdocnombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo documental',
  `tipdocproducedocumento` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el tipo documental produce documento',
  `tipdocactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de documento se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodocumental`
--

INSERT INTO `tipodocumental` (`tipdocid`, `tipdoccodigo`, `tipdocnombre`, `tipdocproducedocumento`, `tipdocactivo`, `created_at`, `updated_at`) VALUES
(1, 'A', 'Acta', 1, 1, NULL, NULL),
(2, 'B', 'Certificado', 1, 1, NULL, NULL),
(3, 'C', 'Circular', 1, 1, NULL, NULL),
(4, 'H', 'Citación', 1, 1, NULL, NULL),
(5, 'T', 'Constancia', 1, 1, NULL, NULL),
(6, 'O', 'Oficio', 1, 1, NULL, NULL),
(7, 'R', 'Resolución', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoasociado`
--

CREATE TABLE `tipoestadoasociado` (
  `tiesasid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo de estado asociado',
  `tiesasnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de estado del asociado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoasociado`
--

INSERT INTO `tipoestadoasociado` (`tiesasid`, `tiesasnombre`) VALUES
('A', 'Activo'),
('E', 'Excluido'),
('I', 'Inactivo'),
('R', 'Retirado'),
('S', 'Sancionado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadocolocacion`
--

CREATE TABLE `tipoestadocolocacion` (
  `tiesclid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo estado solicitud colocación',
  `tiesclnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de estado de la solicitud de colocación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadocolocacion`
--

INSERT INTO `tipoestadocolocacion` (`tiesclid`, `tiesclnombre`) VALUES
('C', 'Cancelado anticipadamente'),
('J', 'Júridica'),
('R', 'Recuperacón'),
('S', 'Saldada'),
('V', 'Vigente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoconductor`
--

CREATE TABLE `tipoestadoconductor` (
  `tiescoid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo estado conductor',
  `tiesconombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de estado del conductor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoconductor`
--

INSERT INTO `tipoestadoconductor` (`tiescoid`, `tiesconombre`) VALUES
('A', 'Activo'),
('D', 'Desvinculado'),
('S', 'Suspendido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadodocumento`
--

CREATE TABLE `tipoestadodocumento` (
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `tiesdonombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo estado documento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadodocumento`
--

INSERT INTO `tipoestadodocumento` (`tiesdoid`, `tiesdonombre`) VALUES
(1, 'Inicial'),
(2, 'Solicitar firma'),
(3, 'Anular la solicitud de firma'),
(4, 'Documento firmado'),
(5, 'Documento sellado'),
(6, 'Documento Radicado'),
(7, 'Documento compartido'),
(8, 'Documento recibido'),
(9, 'Solicitar anulación del documento'),
(10, 'Documento anulado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoraddocentrante`
--

CREATE TABLE `tipoestadoraddocentrante` (
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento entrante',
  `tierdenombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo estado documento entrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoraddocentrante`
--

INSERT INTO `tipoestadoraddocentrante` (`tierdeid`, `tierdenombre`) VALUES
(1, 'Inicial'),
(2, 'Tramitado'),
(3, 'Recibido'),
(4, 'Respondido'),
(5, 'Anulado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadosolicitudcredito`
--

CREATE TABLE `tipoestadosolicitudcredito` (
  `tiesscid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo estado solicitud crédito',
  `tiesscnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de estado de la solicitud de crédito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadosolicitudcredito`
--

INSERT INTO `tipoestadosolicitudcredito` (`tiesscid`, `tiesscnombre`) VALUES
('A', 'Aprobado'),
('D', 'Desembolsado'),
('N', 'Negado'),
('R', 'Registrado'),
('S', 'Asesoria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadovehiculo`
--

CREATE TABLE `tipoestadovehiculo` (
  `tiesveid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo estado vehículo',
  `tiesvenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo estado vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadovehiculo`
--

INSERT INTO `tipoestadovehiculo` (`tiesveid`, `tiesvenombre`) VALUES
('A', 'Activo'),
('D', 'Desvinculado'),
('S', 'Suspendido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestantearchivador`
--

CREATE TABLE `tipoestantearchivador` (
  `tiesarid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estante archivador',
  `tiesarnombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo estante archivador',
  `tiesaractivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el estante archivador se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestantearchivador`
--

INSERT INTO `tipoestantearchivador` (`tiesarid`, `tiesarnombre`, `tiesaractivo`, `created_at`, `updated_at`) VALUES
(1, 'Estante uno', 1, NULL, NULL),
(2, 'Estante dos', 1, NULL, NULL),
(3, 'Estante tres', 1, NULL, NULL),
(4, 'Estante cuatro', 1, NULL, NULL),
(5, 'Estante cinco', 1, NULL, NULL),
(6, 'Estante seis', 1, NULL, NULL),
(7, 'Estante siete', 1, NULL, NULL),
(8, 'Estante ocho', 1, NULL, NULL),
(9, 'Estante nueve', 1, NULL, NULL),
(10, 'Estante diez', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoidentificacion`
--

CREATE TABLE `tipoidentificacion` (
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo identificación',
  `tipidesigla` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla del tipo de identificación',
  `tipidenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de identificación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoidentificacion`
--

INSERT INTO `tipoidentificacion` (`tipideid`, `tipidesigla`, `tipidenombre`) VALUES
(1, 'CC', 'Cédula de ciudadanía'),
(2, 'TI', 'Tarjeta de identidad'),
(3, 'RC', 'Registro civil'),
(4, 'CE', 'Cédula de extranjería'),
(5, 'NIT', 'Número de identificación tributaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomarcavehiculo`
--

CREATE TABLE `tipomarcavehiculo` (
  `timaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo marca vehículo',
  `timavenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la marca del tipo vehículo',
  `timaveactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de marcha del vehículo se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomarcavehiculo`
--

INSERT INTO `tipomarcavehiculo` (`timaveid`, `timavenombre`, `timaveactiva`, `created_at`, `updated_at`) VALUES
(1, 'NISSAN', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(2, 'CHEVROLET', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(3, 'DAIHATSU', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(4, 'RENAULT', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(5, 'HYUNDAI', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(6, 'DAEWOO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(7, 'FORD', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(8, 'KIA', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(9, 'SUZUKI', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(10, 'MITSUBISHI', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(11, 'DFSK', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(12, 'MERCEDES BENZ', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(13, 'JAC', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(14, 'WILLYS', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(15, 'AGRALE', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(16, 'DODGE', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(17, 'INTERNATIONAL', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(18, 'HINO', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(19, 'VOLKSWAGEN', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(20, 'MAZDA', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(21, 'SUSUKI', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(22, 'JEEP', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36'),
(23, 'FOTON', 1, '2023-11-09 09:30:36', '2023-11-09 09:30:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomedio`
--

CREATE TABLE `tipomedio` (
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de medio',
  `tipmednombre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de medio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomedio`
--

INSERT INTO `tipomedio` (`tipmedid`, `tipmednombre`) VALUES
(1, 'Impreso'),
(2, 'Correo'),
(3, 'Impreso / Correo'),
(4, 'Fax'),
(5, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomodalidadvehiculo`
--

CREATE TABLE `tipomodalidadvehiculo` (
  `timoveid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del la tabla tipo modalidad vehículo',
  `timovenombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de modalidad del vehículo',
  `timovetienedespacho` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el tipo modalidad del vehículo tiene despacho'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomodalidadvehiculo`
--

INSERT INTO `tipomodalidadvehiculo` (`timoveid`, `timovenombre`, `timovetienedespacho`) VALUES
(1, 'TODAS', 0),
(2, 'COLECTIVO', 0),
(3, 'URBANO', 0),
(4, 'INTERMUNICIPAL', 1),
(5, 'MIXTO', 1),
(6, 'PRIVADO', 0),
(7, 'ESPECIAL', 0),
(8, 'ESCOLAR', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopersona`
--

CREATE TABLE `tipopersona` (
  `tipperid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador de la tabla tipo de persona',
  `tippernombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de persona'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipopersona`
--

INSERT INTO `tipopersona` (`tipperid`, `tippernombre`) VALUES
('A', 'Asociado'),
('C', 'Conductor'),
('E', 'Empleado'),
('X', 'Externo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopersonadocumental`
--

CREATE TABLE `tipopersonadocumental` (
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de persona documental',
  `tipedonombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de persona documental',
  `tipedoactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de persona documental se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipopersonadocumental`
--

INSERT INTO `tipopersonadocumental` (`tipedoid`, `tipedonombre`, `tipedoactivo`, `created_at`, `updated_at`) VALUES
(1, 'El señor', 1, '2023-11-09 09:30:31', '2023-11-09 09:30:31'),
(2, 'El doctor', 1, '2023-11-09 09:30:31', '2023-11-09 09:30:31'),
(3, 'La doctora', 1, '2023-11-09 09:30:31', '2023-11-09 09:30:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiporeferenciavehiculo`
--

CREATE TABLE `tiporeferenciavehiculo` (
  `tireveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo referencia vehículo',
  `tirevenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo vehículo',
  `tireveactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de referencia del vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiporeferenciavehiculo`
--

INSERT INTO `tiporeferenciavehiculo` (`tireveid`, `tirevenombre`, `tireveactivo`, `created_at`, `updated_at`) VALUES
(1, 'URVAN', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(2, 'NKR-55', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(3, 'NKR', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(4, 'DELTA', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(5, 'NKR-4', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(6, 'TRAFIC', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(7, 'ATOS PRIME GL', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(8, 'CIELO', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(9, 'ATOS PRIME', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(10, 'TAXI 7:24', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(11, 'SYMBOL CITIUS', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(12, 'R-9', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(13, 'TAXI DIESEL', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(14, 'SUPER TAXI', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(15, 'CLIO EXPRESS', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(16, 'ATOS', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(17, 'SPARK', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(18, 'R-9 INYECCIÓN', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(19, 'MATIZ', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(20, 'CIELO BX', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(21, 'SYMBOL', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(22, 'LANOS', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(23, 'TAXI LANOS S', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(24, 'CBX 1047', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35'),
(25, 'LOGAN DYNAMIQUE', 1, '2023-11-09 09:30:35', '2023-11-09 09:30:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposaludo`
--

CREATE TABLE `tiposaludo` (
  `tipsalid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de saludo',
  `tipsalnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de saludo',
  `tipsalactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de saludo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiposaludo`
--

INSERT INTO `tiposaludo` (`tipsalid`, `tipsalnombre`, `tipsalactivo`, `created_at`, `updated_at`) VALUES
(1, 'Apreciado señor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(2, 'Apreciada señora,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(3, 'Apreciado proveedor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(4, 'Cordial saludo,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(5, 'Estimado señor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(6, 'Estimada señora,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(7, 'Estimado cliente,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(8, 'Estimado consultante,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(9, 'Distinguido señor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(10, 'Distinguida señora,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(11, 'Distinguidos señores,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(12, 'Notable señora,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(13, 'Notables señores,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(14, 'Respetable señor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(15, 'Respetable señora,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(16, 'Respetables señores,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(17, 'Amable señor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(18, 'Amable señora,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29'),
(19, 'Notable señor,', 1, '2023-11-09 09:30:29', '2023-11-09 09:30:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposerviciovehiculo`
--

CREATE TABLE `tiposerviciovehiculo` (
  `tiseveid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de servicio del vehículo',
  `tisevenombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de servicio del vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiposerviciovehiculo`
--

INSERT INTO `tiposerviciovehiculo` (`tiseveid`, `tisevenombre`) VALUES
('B', 'Básico'),
('C', 'Común'),
('CR', 'Corriente'),
('E', 'Especial'),
('SS', 'Sin servicio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipotramite`
--

CREATE TABLE `tipotramite` (
  `tiptraid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de trámite',
  `tiptranombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de trámite'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipotramite`
--

INSERT INTO `tipotramite` (`tiptraid`, `tiptranombre`) VALUES
(1, 'Archivar'),
(2, 'Socializar'),
(3, 'Enviar a otra dependencia'),
(4, 'Dar respuesta'),
(5, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculo`
--

CREATE TABLE `tipovehiculo` (
  `tipvehid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo vehículo',
  `tipvehnombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo vehículo',
  `tipvehreferencia` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Referencia del tipo vehículo',
  `tipvehcapacidad` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Capacidad del tipo de vehículo',
  `tipvehnumerofilas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Número de filas del tipo de vehículo',
  `tipvehnumerocolumnas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Número de columnas del tipo de vehículo',
  `tipvehactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipovehiculo`
--

INSERT INTO `tipovehiculo` (`tipvehid`, `tipvehnombre`, `tipvehreferencia`, `tipvehcapacidad`, `tipvehnumerofilas`, `tipvehnumerocolumnas`, `tipvehactivo`, `created_at`, `updated_at`) VALUES
(1, 'AUTOMÓVIL', NULL, 4, 2, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(2, 'MICROBUS', 'URVAN', 9, 4, 3, 0, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(3, 'MICROBUS', '15P', 15, 5, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(4, 'MICROBUS', '19P', 19, 6, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(5, 'MICROBUS', '18P', 18, 6, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(6, 'MICROBUS', '06P', 6, 3, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(7, 'BUS', '26P', 26, 8, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(8, 'BUS', '24P', 24, 7, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(9, 'MICROBUS', 'CARNIVAL', 7, 3, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(10, 'MICROBUS', '20P', 20, 6, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(11, 'MICROBUS', '09P', 9, 4, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(12, 'MICROBUS', 'SPRINTER', 15, 5, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(13, 'MICROBUS', '17P', 17, 6, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(14, 'MICROBUS', '17P2', 17, 6, 4, 0, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(15, 'MICROBUS', '17P3', 17, 6, 4, 0, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(16, 'MICROBUS', '15P3', 15, 5, 4, 0, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(17, 'MICROBUS', '18P2', 18, 6, 5, 0, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(18, 'CAMIONETA', NULL, 7, 3, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(19, 'JEEP', NULL, 5, 3, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(20, 'CAMION', NULL, 8, 2, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(21, 'BUSETA', NULL, 22, 6, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(22, 'MICROBUS', '8P', 8, 4, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(23, 'BUS', '30P', 30, 8, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(24, 'BUS', '28P', 28, 8, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(25, 'BUS', '34P', 34, 9, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(26, 'BUS', '33P', 33, 10, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(27, 'BUS', '32P', 32, 10, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(28, 'BUS', '36P', 36, 11, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(29, 'MICROBUS', '12P', 12, 4, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(30, 'MICROBUS', '16P', 16, 6, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(31, 'MICROBUS', '14P', 14, 6, 4, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(32, 'BUS', '38P', 38, 11, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(33, 'BUS', '37P', 37, 11, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(34, 'BUS', '25P', 25, 7, 5, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(35, 'MICROBUS', '11P', 11, 4, 3, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34'),
(36, 'MOTO', NULL, 1, 1, 1, 1, '2023-11-09 09:30:34', '2023-11-09 09:30:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokenfirmapersona`
--

CREATE TABLE `tokenfirmapersona` (
  `tofipeid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla token firma',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tofipetoken` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token creado aleatoriamente para validar la firma',
  `tofipefechahoranotificacion` datetime NOT NULL COMMENT 'Fecha y hora de la cual se envio la notifiación',
  `tofipefechahoramaxvalidez` datetime NOT NULL COMMENT 'Fecha y hora maxima de validez del token',
  `tofipemensajecorreo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al correo',
  `tofipemensajecelular` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al celular',
  `tofipeutilizado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el token fue utilizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla usuario',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `usuanombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del usuario',
  `usuaapellidos` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Apellidos del usuario',
  `usuaemail` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo del usuario',
  `usuanick` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nick del usuario',
  `usuaalias` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Alias para colocar como transcriptor del documento en la gestion documental',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password del usuario',
  `usuacambiarpassword` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el usuario debe cambar la contraseña para poder inciar sesión',
  `usuabloqueado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el usuario esta bloqueado',
  `usuaactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el usuario esta activo',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuaid`, `persid`, `usuanombre`, `usuaapellidos`, `usuaemail`, `usuanick`, `usuaalias`, `password`, `usuacambiarpassword`, `usuabloqueado`, `usuaactivo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'RAMÓN DAVID', 'SALAZAR RINCÓN', 'radasa10@hotmail.com', 'RSALAZAR', 'Salazar R.', '$2y$10$9F/CLAx9P8UPF8kYRcaEAuKFjpA/FS7z2ynFf1T/Azl21Ar0CE/cC', 0, 0, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariorol`
--

CREATE TABLE `usuariorol` (
  `usurolid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla usuario rol',
  `usurolusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `usurolrolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuariorol`
--

INSERT INTO `usuariorol` (`usurolid`, `usurolusuaid`, `usurolrolid`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo',
  `tipvehid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de vehículo',
  `tireveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de referencia del vehículo',
  `timaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo marca vehículo',
  `ticoveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo color vehículo',
  `timoveid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo modalidad vehículo',
  `ticaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo carroceria vehículo',
  `ticovhid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo combustible vehículo',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia a la que esta asignado el vehículo',
  `tiesveid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo estado vehículo',
  `vehifechaingreso` date NOT NULL COMMENT 'Fecha de ingreso del vehículo a la cooperativa',
  `vehinumerointerno` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número interno del vehículo',
  `vehiplaca` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Placa del vehículo',
  `vehimodelo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Modelo del vehículo',
  `vehicilindraje` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cilindraje del vehículo',
  `vehinumeromotor` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número del motor del vehículo',
  `vehinumerochasis` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número del chasis del vehículo',
  `vehinumeroserie` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número del serie del vehículo',
  `vehinumeroejes` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número del chasis del vehículo',
  `vehiesmotorregrabado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el vehículo tiene motor regrabado',
  `vehieschasisregrabado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el vehículo tiene chasis regrabado',
  `vehiesserieregrabado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el vehículo tiene serie regrabado',
  `vehiobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación general del vehículo',
  `vehirutafoto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la foto del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculocambioestado`
--

CREATE TABLE `vehiculocambioestado` (
  `vecaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo cambio estado',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `tiesveid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de estado vehículo',
  `vecaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del vehículo',
  `vecaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del vehículo',
  `vecaesobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculocrt`
--

CREATE TABLE `vehiculocrt` (
  `vehcrtid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo CRT',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehcrtnumero` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número del CRT del vehículo',
  `vehcrtfechainicial` date NOT NULL COMMENT 'Fecha inicial del CRT del vehículo',
  `vehcrtfechafinal` date NOT NULL COMMENT 'Fecha final del CRT del vehículo',
  `vehcrtextension` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Extensión del archivo que se anexa del CRT del vehículo',
  `vehcrtnombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa del CRT del vehículo',
  `vehcrtnombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa del CRT del vehículo',
  `vehcrtrutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa del CRT del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculopoliza`
--

CREATE TABLE `vehiculopoliza` (
  `vehpolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo póliza',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehpolnumeropolizacontractual` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de póliza contractual del vehículo',
  `vehpolnumeropolizaextcontrac` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de póliza extra contractual del vehículo',
  `vehpolfechainicial` date NOT NULL COMMENT 'Fecha inicial de la póliza del vehículo',
  `vehpolfechafinal` date NOT NULL COMMENT 'Fecha final de la póliza  del vehículo',
  `vehpolextension` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Extensión del archivo que se anexa de la póliza del vehículo',
  `vehpolnombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa de la póliza del vehículo',
  `vehpolnombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa de la póliza del vehículo',
  `vehpolrutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa de la póliza del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculosoat`
--

CREATE TABLE `vehiculosoat` (
  `vehsoaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo SOAT',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehsoanumero` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número del SOAT del vehículo',
  `vehsoafechainicial` date NOT NULL COMMENT 'Fecha inicial del SOAT del vehículo',
  `vehsoafechafinal` date NOT NULL COMMENT 'Fecha final del SOAT del vehículo',
  `vehsoaextension` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Extensión del archivo que se anexa del SOAT del vehículo',
  `vehsoanombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa del SOAT del vehículo',
  `vehsoanombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa del SOAT del vehículo',
  `vehsoarutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa del SOAT del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculotarjetaoperacion`
--

CREATE TABLE `vehiculotarjetaoperacion` (
  `vetaopaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo tarjeta operación',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `tiseveid` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador del tipo de servicio del vehículo',
  `vetaopnumero` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de la tarjeta de operación del vehículo',
  `vetaopfechainicial` date NOT NULL COMMENT 'Fecha inicial de la tarjeta de operación del vehículo',
  `vetaopfechafinal` date NOT NULL COMMENT 'Fecha final de la tarjeta de operación del vehículo',
  `vetaopenteadministrativo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ente administrativo que emite la tarjeta de operación del vehículo',
  `vetaopradioaccion` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Radio de acción de la tarjeta de operación del vehículo',
  `vetaopextension` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Extensión del archivo que se anexa a la tarjeta de operación del vehículo',
  `vetaopnombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa a la tarjeta de operación del vehículo',
  `vetaopnombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa a la tarjeta de operación del vehículo',
  `vetaoprutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa a la tarjeta de operación del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agencia`
--
ALTER TABLE `agencia`
  ADD PRIMARY KEY (`agenid`),
  ADD KEY `fk_depaagen` (`agendepaid`),
  ADD KEY `fk_muniagen` (`agenmuniid`),
  ADD KEY `fk_persagen` (`persidresponsable`);

--
-- Indices de la tabla `archivohistorico`
--
ALTER TABLE `archivohistorico`
  ADD PRIMARY KEY (`archisid`),
  ADD KEY `fk_tipdocarchis` (`tipdocid`),
  ADD KEY `fk_usuaarchis` (`usuaid`),
  ADD KEY `fk_tiesararchis` (`tiesarid`),
  ADD KEY `fk_ticauarchis` (`ticaubid`),
  ADD KEY `fk_ticrubarchis` (`ticrubid`);

--
-- Indices de la tabla `archivohistoricodigitalizado`
--
ALTER TABLE `archivohistoricodigitalizado`
  ADD PRIMARY KEY (`arhidiid`),
  ADD KEY `fk_archisarhidi` (`archisid`);

--
-- Indices de la tabla `asociado`
--
ALTER TABLE `asociado`
  ADD PRIMARY KEY (`asocid`),
  ADD KEY `fk_persasoc` (`persid`),
  ADD KEY `fk_tiesasasoc` (`tiesasid`);

--
-- Indices de la tabla `asociadocambioestado`
--
ALTER TABLE `asociadocambioestado`
  ADD PRIMARY KEY (`ascaesid`),
  ADD KEY `fk_asocascaes` (`asocid`),
  ADD KEY `fk_tiesasascaes` (`tiesasid`),
  ADD KEY `fk_usuaascaes` (`ascaesusuaid`);

--
-- Indices de la tabla `asociadovehiculo`
--
ALTER TABLE `asociadovehiculo`
  ADD PRIMARY KEY (`asovehid`),
  ADD KEY `fk_asocasoveh` (`asocid`),
  ADD KEY `fk_vehiasoveh` (`vehiid`);

--
-- Indices de la tabla `cargolaboral`
--
ALTER TABLE `cargolaboral`
  ADD PRIMARY KEY (`carlabid`);

--
-- Indices de la tabla `coddocumprocesoacta`
--
ALTER TABLE `coddocumprocesoacta`
  ADD PRIMARY KEY (`codopaid`),
  ADD UNIQUE KEY `uk_coddocumprocesoacta` (`codopaconsecutivo`,`codopasigla`,`codopaanio`),
  ADD KEY `fk_codoprcodopa` (`codoprid`),
  ADD KEY `fk_tipactcodopa` (`tipactid`),
  ADD KEY `fk_usuacodopa` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesoanexo`
--
ALTER TABLE `coddocumprocesoanexo`
  ADD PRIMARY KEY (`codopxid`),
  ADD KEY `fk_codoprcodopx` (`codoprid`);

--
-- Indices de la tabla `coddocumprocesocambioestado`
--
ALTER TABLE `coddocumprocesocambioestado`
  ADD PRIMARY KEY (`codpceid`),
  ADD KEY `fk_codoprcodpce` (`codoprid`),
  ADD KEY `fk_tiesdocodpce` (`tiesdoid`),
  ADD KEY `fk_usuacodpce` (`codpceusuaid`);

--
-- Indices de la tabla `coddocumprocesocertificado`
--
ALTER TABLE `coddocumprocesocertificado`
  ADD PRIMARY KEY (`codopcid`),
  ADD UNIQUE KEY `uk_coddocumprocesocertificado` (`codopcconsecutivo`,`codopcsigla`,`codopcanio`),
  ADD KEY `fk_codoprcodopc` (`codoprid`),
  ADD KEY `fk_tippedocodopc` (`tipedoid`),
  ADD KEY `fk_usuacodopc` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesocircular`
--
ALTER TABLE `coddocumprocesocircular`
  ADD PRIMARY KEY (`codoplid`),
  ADD UNIQUE KEY `uk_coddocumprocesocircular` (`codoplconsecutivo`,`codoplsigla`,`codoplanio`),
  ADD KEY `fk_codoprcodopl` (`codoprid`),
  ADD KEY `fk_usuacodopl` (`usuaid`),
  ADD KEY `fk_tipdescodopl` (`tipdesid`);

--
-- Indices de la tabla `coddocumprocesocitacion`
--
ALTER TABLE `coddocumprocesocitacion`
  ADD PRIMARY KEY (`codoptid`),
  ADD UNIQUE KEY `uk_coddocumprocesocitacion` (`codoptconsecutivo`,`codoptsigla`,`codoptanio`),
  ADD KEY `fk_codoprcodopt` (`codoprid`),
  ADD KEY `fk_tipactcodopt` (`tipactid`),
  ADD KEY `fk_usuacodopt` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesocompartido`
--
ALTER TABLE `coddocumprocesocompartido`
  ADD PRIMARY KEY (`codopdid`),
  ADD KEY `fk_codoprcodopd` (`codoprid`),
  ADD KEY `fk_usuacodopd` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesoconstancia`
--
ALTER TABLE `coddocumprocesoconstancia`
  ADD PRIMARY KEY (`codopnid`),
  ADD UNIQUE KEY `uk_coddocumprocesocontancia` (`codopnconsecutivo`,`codopnsigla`,`codopnanio`),
  ADD KEY `fk_codoprcodopn` (`codoprid`),
  ADD KEY `fk_tippedocodopn` (`tipedoid`),
  ADD KEY `fk_usuacodopn` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesocopia`
--
ALTER TABLE `coddocumprocesocopia`
  ADD PRIMARY KEY (`codoppid`),
  ADD KEY `fk_codoprcodopp` (`codoprid`),
  ADD KEY `fk_depecodopp` (`depeid`);

--
-- Indices de la tabla `coddocumprocesofirma`
--
ALTER TABLE `coddocumprocesofirma`
  ADD PRIMARY KEY (`codopfid`),
  ADD KEY `fk_codoprcodopf` (`codoprid`),
  ADD KEY `fk_perscodopf` (`persid`),
  ADD KEY `fk_carlabcodopf` (`carlabid`);

--
-- Indices de la tabla `coddocumprocesooficio`
--
ALTER TABLE `coddocumprocesooficio`
  ADD PRIMARY KEY (`codopoid`),
  ADD UNIQUE KEY `uk_coddocumprocesooficio` (`codopoconsecutivo`,`codoposigla`,`codopoanio`),
  ADD KEY `fk_codoprcodopo` (`codoprid`),
  ADD KEY `fk_usuacodopo` (`usuaid`),
  ADD KEY `fk_tipsalcodopo` (`tipsalid`),
  ADD KEY `fk_tipdescodopo` (`tipdesid`);

--
-- Indices de la tabla `coddocumprocesoraddocentrante`
--
ALTER TABLE `coddocumprocesoraddocentrante`
  ADD PRIMARY KEY (`cdprdeid`),
  ADD KEY `fk_codoprcdprde` (`codoprid`),
  ADD KEY `fk_radoencdprde` (`radoenid`);

--
-- Indices de la tabla `codigodocumental`
--
ALTER TABLE `codigodocumental`
  ADD PRIMARY KEY (`coddocid`),
  ADD KEY `fk_depecoddoc` (`depeid`),
  ADD KEY `fk_serdoccoddoc` (`serdocid`),
  ADD KEY `fk_susedocoddoc` (`susedoid`),
  ADD KEY `fk_tipdoccoddoc` (`tipdocid`),
  ADD KEY `fk_tipmedcoddoc` (`tipmedid`),
  ADD KEY `fk_tiptracoddoc` (`tiptraid`),
  ADD KEY `fk_tipdepcoddoc` (`tipdetid`),
  ADD KEY `fk_coddocuser` (`usuaid`);

--
-- Indices de la tabla `codigodocumentalproceso`
--
ALTER TABLE `codigodocumentalproceso`
  ADD PRIMARY KEY (`codoprid`),
  ADD KEY `fk_coddoccodopr` (`coddocid`),
  ADD KEY `fk_tiesdocodopr` (`tiesdoid`);

--
-- Indices de la tabla `colocacion`
--
ALTER TABLE `colocacion`
  ADD PRIMARY KEY (`coloid`),
  ADD UNIQUE KEY `uk_colocacion` (`coloanio`,`colonumerodesembolso`),
  ADD KEY `fk_usuacolo` (`usuaid`),
  ADD KEY `fk_solcrecolo` (`solcreid`),
  ADD KEY `fk_tiesclcolo` (`tiesclid`);

--
-- Indices de la tabla `colocacioncambioestado`
--
ALTER TABLE `colocacioncambioestado`
  ADD PRIMARY KEY (`cocaesid`),
  ADD KEY `fk_colococaes` (`coloid`),
  ADD KEY `fk_tiesclcocaes` (`tiesclid`),
  ADD KEY `fk_usuaclcaes` (`cocaesusuaid`);

--
-- Indices de la tabla `colocacionliquidacion`
--
ALTER TABLE `colocacionliquidacion`
  ADD PRIMARY KEY (`colliqid`),
  ADD KEY `fk_colocolliq` (`coloid`);

--
-- Indices de la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`condid`),
  ADD KEY `fk_perscond` (`persid`),
  ADD KEY `fk_tiescocond` (`tiescoid`),
  ADD KEY `fk_tipconcond` (`tipconid`),
  ADD KEY `fk_agencond` (`agenid`);

--
-- Indices de la tabla `conductorcambioestado`
--
ALTER TABLE `conductorcambioestado`
  ADD PRIMARY KEY (`cocaesid`),
  ADD KEY `fk_condcocaes` (`condid`),
  ADD KEY `fk_tiesascocaes` (`tiescoid`),
  ADD KEY `fk_usuacocaes` (`cocaesusuaid`);

--
-- Indices de la tabla `conductorlicencia`
--
ALTER TABLE `conductorlicencia`
  ADD PRIMARY KEY (`conlicid`),
  ADD KEY `fk_condconlic` (`condid`),
  ADD KEY `fk_ticaliconlic` (`ticaliid`);

--
-- Indices de la tabla `conductorvehiculo`
--
ALTER TABLE `conductorvehiculo`
  ADD PRIMARY KEY (`convehid`),
  ADD KEY `fk_condconveh` (`condid`),
  ADD KEY `fk_vehiconveh` (`vehiid`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`depaid`),
  ADD UNIQUE KEY `uk_departamento` (`depacodigo`);

--
-- Indices de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD PRIMARY KEY (`depeid`),
  ADD UNIQUE KEY `uk_dependencia1` (`depecodigo`),
  ADD UNIQUE KEY `uk_dependencia2` (`depesigla`),
  ADD KEY `fk_persdepe` (`depejefeid`);

--
-- Indices de la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  ADD PRIMARY KEY (`depperid`),
  ADD UNIQUE KEY `uk_dependenciapersona` (`depperdepeid`,`depperpersid`),
  ADD KEY `fk_persdepper` (`depperpersid`);

--
-- Indices de la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  ADD PRIMARY KEY (`desusdid`),
  ADD KEY `fk_susedodesusd` (`desusdsusedoid`),
  ADD KEY `fk_depedesusd` (`desusddepeid`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`emprid`),
  ADD KEY `fk_depaempr` (`emprdepaid`),
  ADD KEY `fk_muniempr` (`emprmuniid`),
  ADD KEY `fk_persempr` (`persidrepresentantelegal`);

--
-- Indices de la tabla `festivo`
--
ALTER TABLE `festivo`
  ADD PRIMARY KEY (`festid`);

--
-- Indices de la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  ADD PRIMARY KEY (`funcid`),
  ADD KEY `fk_modufunc` (`moduid`);

--
-- Indices de la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  ADD PRIMARY KEY (`hisconid`),
  ADD KEY `fk_usuahiscon` (`usuaid`);

--
-- Indices de la tabla `informacionconfiguracioncorreo`
--
ALTER TABLE `informacionconfiguracioncorreo`
  ADD PRIMARY KEY (`incocoid`);

--
-- Indices de la tabla `informaciongeneralpdf`
--
ALTER TABLE `informaciongeneralpdf`
  ADD PRIMARY KEY (`ingpdfid`),
  ADD UNIQUE KEY `uk_informaciongeneralpdf` (`ingpdfnombre`);

--
-- Indices de la tabla `informacionnotificacioncorreo`
--
ALTER TABLE `informacionnotificacioncorreo`
  ADD PRIMARY KEY (`innocoid`),
  ADD UNIQUE KEY `uk_infornotificacioncorreo` (`innoconombre`);

--
-- Indices de la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  ADD PRIMARY KEY (`ingsisid`),
  ADD KEY `fk_usuaingsis` (`usuaid`);

--
-- Indices de la tabla `intentosfallidos`
--
ALTER TABLE `intentosfallidos`
  ADD PRIMARY KEY (`intfalid`);

--
-- Indices de la tabla `lineacredito`
--
ALTER TABLE `lineacredito`
  ADD PRIMARY KEY (`lincreid`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`moduid`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`muniid`),
  ADD UNIQUE KEY `uk_municipio` (`municodigo`),
  ADD KEY `fk_depamuni` (`munidepaid`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`persid`),
  ADD UNIQUE KEY `uk_personatipoidentificacion` (`tipideid`,`persdocumento`),
  ADD KEY `fk_carglabpers` (`carlabid`),
  ADD KEY `fk_tipperpers` (`tipperid`),
  ADD KEY `fk_depapersnac` (`persdepaidnacimiento`),
  ADD KEY `fk_munipersnac` (`persmuniidnacimiento`),
  ADD KEY `fk_depapersexp` (`persdepaidexpedicion`),
  ADD KEY `fk_munipersexp` (`persmuniidexpedicion`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  ADD PRIMARY KEY (`peradoid`),
  ADD UNIQUE KEY `uk_personaradicadocumento` (`tipideid`,`peradodocumento`);

--
-- Indices de la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  ADD PRIMARY KEY (`radoeaid`),
  ADD KEY `fk_radoenradoea` (`radoenid`);

--
-- Indices de la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  ADD PRIMARY KEY (`radeceid`),
  ADD KEY `fk_radoenradece` (`radoenid`),
  ADD KEY `fk_tierderadece` (`tierdeid`),
  ADD KEY `fk_usuaradece` (`radeceusuaid`);

--
-- Indices de la tabla `radicaciondocentdependencia`
--
ALTER TABLE `radicaciondocentdependencia`
  ADD PRIMARY KEY (`radoedid`),
  ADD KEY `fk_radoenradoed` (`radoenid`),
  ADD KEY `fk_deperadoed` (`depeid`),
  ADD KEY `fk_usuaradoed` (`radoedsuaid`);

--
-- Indices de la tabla `radicaciondocumentoentrante`
--
ALTER TABLE `radicaciondocumentoentrante`
  ADD PRIMARY KEY (`radoenid`),
  ADD UNIQUE KEY `uk_radicaciondocumentoentrante` (`radoenconsecutivo`,`radoenanio`),
  ADD KEY `fk_peradoradoen` (`peradoid`),
  ADD KEY `fk_tipmedradoen` (`tipmedid`),
  ADD KEY `fk_tierderadoen` (`tierdeid`),
  ADD KEY `fk_deparadoen` (`depaid`),
  ADD KEY `fk_muniradoen` (`muniid`),
  ADD KEY `fk_radoen` (`usuaid`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rolid`);

--
-- Indices de la tabla `rolfuncionalidad`
--
ALTER TABLE `rolfuncionalidad`
  ADD PRIMARY KEY (`rolfunid`),
  ADD KEY `fk_rolrolfun` (`rolfunrolid`),
  ADD KEY `fk_funcrolfun` (`rolfunfuncid`);

--
-- Indices de la tabla `seriedocumental`
--
ALTER TABLE `seriedocumental`
  ADD PRIMARY KEY (`serdocid`),
  ADD UNIQUE KEY `uk_serie` (`serdoccodigo`);

--
-- Indices de la tabla `solicitudcredito`
--
ALTER TABLE `solicitudcredito`
  ADD PRIMARY KEY (`solcreid`),
  ADD KEY `fk_usuasolcre` (`usuaid`),
  ADD KEY `fk_lincresolcre` (`lincreid`),
  ADD KEY `fk_asocsolcre` (`asocid`),
  ADD KEY `fk_vehisolcre` (`vehiid`),
  ADD KEY `fk_tiesscsolcre` (`tiesscid`);

--
-- Indices de la tabla `solicitudcreditocambioestado`
--
ALTER TABLE `solicitudcreditocambioestado`
  ADD PRIMARY KEY (`socrceid`),
  ADD KEY `fk_solcresocrce` (`solcreid`),
  ADD KEY `fk_tiesscsocrce` (`tiesscid`),
  ADD KEY `fk_usuasocrce` (`socrceusuaid`);

--
-- Indices de la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  ADD PRIMARY KEY (`susedoid`),
  ADD UNIQUE KEY `uk_serdocsusedo` (`serdocid`,`susedocodigo`),
  ADD KEY `fk_tipdocsusedo` (`tipdocid`);

--
-- Indices de la tabla `tipoacta`
--
ALTER TABLE `tipoacta`
  ADD PRIMARY KEY (`tipactid`);

--
-- Indices de la tabla `tipocajaubicacion`
--
ALTER TABLE `tipocajaubicacion`
  ADD PRIMARY KEY (`ticaubid`);

--
-- Indices de la tabla `tipocarpetaubicacion`
--
ALTER TABLE `tipocarpetaubicacion`
  ADD PRIMARY KEY (`ticrubid`);

--
-- Indices de la tabla `tipocarroceriavehiculo`
--
ALTER TABLE `tipocarroceriavehiculo`
  ADD PRIMARY KEY (`ticaveid`);

--
-- Indices de la tabla `tipocategorialicencia`
--
ALTER TABLE `tipocategorialicencia`
  ADD PRIMARY KEY (`ticaliid`);

--
-- Indices de la tabla `tipocolorvehiculo`
--
ALTER TABLE `tipocolorvehiculo`
  ADD PRIMARY KEY (`ticoveid`);

--
-- Indices de la tabla `tipocombustiblevehiculo`
--
ALTER TABLE `tipocombustiblevehiculo`
  ADD PRIMARY KEY (`ticovhid`);

--
-- Indices de la tabla `tipoconductor`
--
ALTER TABLE `tipoconductor`
  ADD PRIMARY KEY (`tipconid`);

--
-- Indices de la tabla `tipodespedida`
--
ALTER TABLE `tipodespedida`
  ADD PRIMARY KEY (`tipdesid`);

--
-- Indices de la tabla `tipodestino`
--
ALTER TABLE `tipodestino`
  ADD PRIMARY KEY (`tipdetid`);

--
-- Indices de la tabla `tipodocumental`
--
ALTER TABLE `tipodocumental`
  ADD PRIMARY KEY (`tipdocid`),
  ADD UNIQUE KEY `uk_tipodocumental` (`tipdoccodigo`);

--
-- Indices de la tabla `tipoestadoasociado`
--
ALTER TABLE `tipoestadoasociado`
  ADD PRIMARY KEY (`tiesasid`);

--
-- Indices de la tabla `tipoestadocolocacion`
--
ALTER TABLE `tipoestadocolocacion`
  ADD PRIMARY KEY (`tiesclid`);

--
-- Indices de la tabla `tipoestadoconductor`
--
ALTER TABLE `tipoestadoconductor`
  ADD PRIMARY KEY (`tiescoid`);

--
-- Indices de la tabla `tipoestadodocumento`
--
ALTER TABLE `tipoestadodocumento`
  ADD PRIMARY KEY (`tiesdoid`);

--
-- Indices de la tabla `tipoestadoraddocentrante`
--
ALTER TABLE `tipoestadoraddocentrante`
  ADD PRIMARY KEY (`tierdeid`);

--
-- Indices de la tabla `tipoestadosolicitudcredito`
--
ALTER TABLE `tipoestadosolicitudcredito`
  ADD PRIMARY KEY (`tiesscid`);

--
-- Indices de la tabla `tipoestadovehiculo`
--
ALTER TABLE `tipoestadovehiculo`
  ADD PRIMARY KEY (`tiesveid`);

--
-- Indices de la tabla `tipoestantearchivador`
--
ALTER TABLE `tipoestantearchivador`
  ADD PRIMARY KEY (`tiesarid`);

--
-- Indices de la tabla `tipoidentificacion`
--
ALTER TABLE `tipoidentificacion`
  ADD PRIMARY KEY (`tipideid`),
  ADD UNIQUE KEY `uk_tipoidentificacion` (`tipidesigla`);

--
-- Indices de la tabla `tipomarcavehiculo`
--
ALTER TABLE `tipomarcavehiculo`
  ADD PRIMARY KEY (`timaveid`);

--
-- Indices de la tabla `tipomedio`
--
ALTER TABLE `tipomedio`
  ADD PRIMARY KEY (`tipmedid`);

--
-- Indices de la tabla `tipomodalidadvehiculo`
--
ALTER TABLE `tipomodalidadvehiculo`
  ADD PRIMARY KEY (`timoveid`);

--
-- Indices de la tabla `tipopersona`
--
ALTER TABLE `tipopersona`
  ADD PRIMARY KEY (`tipperid`);

--
-- Indices de la tabla `tipopersonadocumental`
--
ALTER TABLE `tipopersonadocumental`
  ADD PRIMARY KEY (`tipedoid`),
  ADD KEY `pk_tipedo` (`tipedoid`);

--
-- Indices de la tabla `tiporeferenciavehiculo`
--
ALTER TABLE `tiporeferenciavehiculo`
  ADD PRIMARY KEY (`tireveid`);

--
-- Indices de la tabla `tiposaludo`
--
ALTER TABLE `tiposaludo`
  ADD PRIMARY KEY (`tipsalid`);

--
-- Indices de la tabla `tiposerviciovehiculo`
--
ALTER TABLE `tiposerviciovehiculo`
  ADD PRIMARY KEY (`tiseveid`);

--
-- Indices de la tabla `tipotramite`
--
ALTER TABLE `tipotramite`
  ADD PRIMARY KEY (`tiptraid`);

--
-- Indices de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  ADD PRIMARY KEY (`tipvehid`);

--
-- Indices de la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  ADD PRIMARY KEY (`tofipeid`),
  ADD UNIQUE KEY `uk_tokenfirma` (`tofipetoken`),
  ADD KEY `fk_perstofipe` (`persid`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuaid`),
  ADD UNIQUE KEY `uk_usuario` (`usuaemail`),
  ADD UNIQUE KEY `uk_usuario1` (`usuanick`),
  ADD KEY `fk_persusua` (`persid`);

--
-- Indices de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD PRIMARY KEY (`usurolid`),
  ADD KEY `fk_usuausurol` (`usurolusuaid`),
  ADD KEY `fk_rolusurol` (`usurolrolid`),
  ADD KEY `pk_usurol` (`usurolid`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`vehiid`),
  ADD UNIQUE KEY `uk_vehiculoplaca` (`vehiplaca`),
  ADD KEY `fk_tipvehvehi` (`tipvehid`),
  ADD KEY `fk_tirevevehi` (`tireveid`),
  ADD KEY `fk_timavevehi` (`timaveid`),
  ADD KEY `fk_ticovevehi` (`ticoveid`),
  ADD KEY `fk_timovevehi` (`timoveid`),
  ADD KEY `fk_ticavevehi` (`ticaveid`),
  ADD KEY `fk_ticovhvehi` (`ticovhid`),
  ADD KEY `fk_agenvehi` (`agenid`),
  ADD KEY `fk_tiesvevehi` (`tiesveid`);

--
-- Indices de la tabla `vehiculocambioestado`
--
ALTER TABLE `vehiculocambioestado`
  ADD PRIMARY KEY (`vecaesid`),
  ADD KEY `fk_vehivecaes` (`vehiid`),
  ADD KEY `fk_tiesvevecaes` (`tiesveid`),
  ADD KEY `fk_usuavecaes` (`vecaesusuaid`);

--
-- Indices de la tabla `vehiculocrt`
--
ALTER TABLE `vehiculocrt`
  ADD PRIMARY KEY (`vehcrtid`),
  ADD KEY `fk_vehivehcrt` (`vehiid`);

--
-- Indices de la tabla `vehiculopoliza`
--
ALTER TABLE `vehiculopoliza`
  ADD PRIMARY KEY (`vehpolid`),
  ADD KEY `fk_vehivehpol` (`vehiid`);

--
-- Indices de la tabla `vehiculosoat`
--
ALTER TABLE `vehiculosoat`
  ADD PRIMARY KEY (`vehsoaid`),
  ADD KEY `fk_vehivehsoa` (`vehiid`);

--
-- Indices de la tabla `vehiculotarjetaoperacion`
--
ALTER TABLE `vehiculotarjetaoperacion`
  ADD PRIMARY KEY (`vetaopaid`),
  ADD KEY `fk_vehivetaop` (`vehiid`),
  ADD KEY `fk_tisevevetaop` (`tiseveid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agencia`
--
ALTER TABLE `agencia`
  MODIFY `agenid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla agencia', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `archivohistorico`
--
ALTER TABLE `archivohistorico`
  MODIFY `archisid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla archivo histórico';

--
-- AUTO_INCREMENT de la tabla `archivohistoricodigitalizado`
--
ALTER TABLE `archivohistoricodigitalizado`
  MODIFY `arhidiid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `asociado`
--
ALTER TABLE `asociado`
  MODIFY `asocid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla asociado';

--
-- AUTO_INCREMENT de la tabla `asociadocambioestado`
--
ALTER TABLE `asociadocambioestado`
  MODIFY `ascaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla asociado cambio estado';

--
-- AUTO_INCREMENT de la tabla `asociadovehiculo`
--
ALTER TABLE `asociadovehiculo`
  MODIFY `asovehid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla asociado vehículo';

--
-- AUTO_INCREMENT de la tabla `cargolaboral`
--
ALTER TABLE `cargolaboral`
  MODIFY `carlabid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla cargo laboral', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoacta`
--
ALTER TABLE `coddocumprocesoacta`
  MODIFY `codopaid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso acta';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoanexo`
--
ALTER TABLE `coddocumprocesoanexo`
  MODIFY `codopxid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso anexo';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocambioestado`
--
ALTER TABLE `coddocumprocesocambioestado`
  MODIFY `codpceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso cambio estado';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocertificado`
--
ALTER TABLE `coddocumprocesocertificado`
  MODIFY `codopcid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso certificado';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocircular`
--
ALTER TABLE `coddocumprocesocircular`
  MODIFY `codoplid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso circular';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocitacion`
--
ALTER TABLE `coddocumprocesocitacion`
  MODIFY `codoptid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso citación';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocompartido`
--
ALTER TABLE `coddocumprocesocompartido`
  MODIFY `codopdid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso compartido';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoconstancia`
--
ALTER TABLE `coddocumprocesoconstancia`
  MODIFY `codopnid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso constancia';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocopia`
--
ALTER TABLE `coddocumprocesocopia`
  MODIFY `codoppid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso copia';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesofirma`
--
ALTER TABLE `coddocumprocesofirma`
  MODIFY `codopfid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso firma';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesooficio`
--
ALTER TABLE `coddocumprocesooficio`
  MODIFY `codopoid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso oficio';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoraddocentrante`
--
ALTER TABLE `coddocumprocesoraddocentrante`
  MODIFY `cdprdeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso radicado documento entrante';

--
-- AUTO_INCREMENT de la tabla `codigodocumental`
--
ALTER TABLE `codigodocumental`
  MODIFY `coddocid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental';

--
-- AUTO_INCREMENT de la tabla `codigodocumentalproceso`
--
ALTER TABLE `codigodocumentalproceso`
  MODIFY `codoprid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso';

--
-- AUTO_INCREMENT de la tabla `colocacion`
--
ALTER TABLE `colocacion`
  MODIFY `coloid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla solicitud de credito desembolso';

--
-- AUTO_INCREMENT de la tabla `colocacioncambioestado`
--
ALTER TABLE `colocacioncambioestado`
  MODIFY `cocaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla colocación cambio estado';

--
-- AUTO_INCREMENT de la tabla `colocacionliquidacion`
--
ALTER TABLE `colocacionliquidacion`
  MODIFY `colliqid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla colocación liquidación';

--
-- AUTO_INCREMENT de la tabla `conductor`
--
ALTER TABLE `conductor`
  MODIFY `condid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor';

--
-- AUTO_INCREMENT de la tabla `conductorcambioestado`
--
ALTER TABLE `conductorcambioestado`
  MODIFY `cocaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor cambio estado';

--
-- AUTO_INCREMENT de la tabla `conductorlicencia`
--
ALTER TABLE `conductorlicencia`
  MODIFY `conlicid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor licencia';

--
-- AUTO_INCREMENT de la tabla `conductorvehiculo`
--
ALTER TABLE `conductorvehiculo`
  MODIFY `convehid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor vehículo';

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `depeid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  MODIFY `depperid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia persona', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  MODIFY `desusdid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia sub serie documental', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `emprid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla empresa', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `festivo`
--
ALTER TABLE `festivo`
  MODIFY `festid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla festivo';

--
-- AUTO_INCREMENT de la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  MODIFY `funcid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla funcionalidad', AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  MODIFY `hisconid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla historial de contrasena';

--
-- AUTO_INCREMENT de la tabla `informaciongeneralpdf`
--
ALTER TABLE `informaciongeneralpdf`
  MODIFY `ingpdfid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla información general PDF', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `informacionnotificacioncorreo`
--
ALTER TABLE `informacionnotificacioncorreo`
  MODIFY `innocoid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla informacion notificación correo', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  MODIFY `ingsisid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla ingreso sistema', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `intentosfallidos`
--
ALTER TABLE `intentosfallidos`
  MODIFY `intfalid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla intentos fallidos';

--
-- AUTO_INCREMENT de la tabla `lineacredito`
--
ALTER TABLE `lineacredito`
  MODIFY `lincreid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla línea de crédito';

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `moduid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla módulo', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `muniid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla municipio', AUTO_INCREMENT=1123;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `persid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  MODIFY `peradoid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona radica documento';

--
-- AUTO_INCREMENT de la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  MODIFY `radoeaid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  MODIFY `radeceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante cambio estado';

--
-- AUTO_INCREMENT de la tabla `radicaciondocentdependencia`
--
ALTER TABLE `radicaciondocentdependencia`
  MODIFY `radoedid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `radicaciondocumentoentrante`
--
ALTER TABLE `radicaciondocumentoentrante`
  MODIFY `radoenid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante';

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rolid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla rol', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rolfuncionalidad`
--
ALTER TABLE `rolfuncionalidad`
  MODIFY `rolfunid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla rol funcionalidad', AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `seriedocumental`
--
ALTER TABLE `seriedocumental`
  MODIFY `serdocid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla serie documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `solicitudcredito`
--
ALTER TABLE `solicitudcredito`
  MODIFY `solcreid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla solicitud crédito';

--
-- AUTO_INCREMENT de la tabla `solicitudcreditocambioestado`
--
ALTER TABLE `solicitudcreditocambioestado`
  MODIFY `socrceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla solicitud de credito cambio estado';

--
-- AUTO_INCREMENT de la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  MODIFY `susedoid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla sub serie documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipocarroceriavehiculo`
--
ALTER TABLE `tipocarroceriavehiculo`
  MODIFY `ticaveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo carroceria vehículo', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipocolorvehiculo`
--
ALTER TABLE `tipocolorvehiculo`
  MODIFY `ticoveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo color vehículo', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tipodespedida`
--
ALTER TABLE `tipodespedida`
  MODIFY `tipdesid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo despedida', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `tipodocumental`
--
ALTER TABLE `tipodocumental`
  MODIFY `tipdocid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipoestantearchivador`
--
ALTER TABLE `tipoestantearchivador`
  MODIFY `tiesarid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo estante archivador', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipomarcavehiculo`
--
ALTER TABLE `tipomarcavehiculo`
  MODIFY `timaveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo marca vehículo', AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tipopersonadocumental`
--
ALTER TABLE `tipopersonadocumental`
  MODIFY `tipedoid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo de persona documental', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tiporeferenciavehiculo`
--
ALTER TABLE `tiporeferenciavehiculo`
  MODIFY `tireveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo referencia vehículo', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tiposaludo`
--
ALTER TABLE `tiposaludo`
  MODIFY `tipsalid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo de saludo', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  MODIFY `tipvehid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo vehículo', AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  MODIFY `tofipeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla token firma';

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuaid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla usuario', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  MODIFY `usurolid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla usuario rol', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `vehiid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo';

--
-- AUTO_INCREMENT de la tabla `vehiculocambioestado`
--
ALTER TABLE `vehiculocambioestado`
  MODIFY `vecaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo cambio estado';

--
-- AUTO_INCREMENT de la tabla `vehiculocrt`
--
ALTER TABLE `vehiculocrt`
  MODIFY `vehcrtid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo CRT';

--
-- AUTO_INCREMENT de la tabla `vehiculopoliza`
--
ALTER TABLE `vehiculopoliza`
  MODIFY `vehpolid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo póliza';

--
-- AUTO_INCREMENT de la tabla `vehiculosoat`
--
ALTER TABLE `vehiculosoat`
  MODIFY `vehsoaid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo SOAT';

--
-- AUTO_INCREMENT de la tabla `vehiculotarjetaoperacion`
--
ALTER TABLE `vehiculotarjetaoperacion`
  MODIFY `vetaopaid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo tarjeta operación';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agencia`
--
ALTER TABLE `agencia`
  ADD CONSTRAINT `fk_depaagen` FOREIGN KEY (`agendepaid`) REFERENCES `departamento` (`depaid`),
  ADD CONSTRAINT `fk_muniagen` FOREIGN KEY (`agenmuniid`) REFERENCES `municipio` (`muniid`),
  ADD CONSTRAINT `fk_persagen` FOREIGN KEY (`persidresponsable`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `archivohistorico`
--
ALTER TABLE `archivohistorico`
  ADD CONSTRAINT `fk_ticauarchis` FOREIGN KEY (`ticaubid`) REFERENCES `tipocajaubicacion` (`ticaubid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticrubarchis` FOREIGN KEY (`ticrubid`) REFERENCES `tipocarpetaubicacion` (`ticrubid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesararchis` FOREIGN KEY (`tiesarid`) REFERENCES `tipoestantearchivador` (`tiesarid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdocarchis` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaarchis` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `archivohistoricodigitalizado`
--
ALTER TABLE `archivohistoricodigitalizado`
  ADD CONSTRAINT `fk_archisarhidi` FOREIGN KEY (`archisid`) REFERENCES `archivohistorico` (`archisid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asociado`
--
ALTER TABLE `asociado`
  ADD CONSTRAINT `fk_persasoc` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesasasoc` FOREIGN KEY (`tiesasid`) REFERENCES `tipoestadoasociado` (`tiesasid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asociadocambioestado`
--
ALTER TABLE `asociadocambioestado`
  ADD CONSTRAINT `fk_asocascaes` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesasascaes` FOREIGN KEY (`tiesasid`) REFERENCES `tipoestadoasociado` (`tiesasid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaascaes` FOREIGN KEY (`ascaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asociadovehiculo`
--
ALTER TABLE `asociadovehiculo`
  ADD CONSTRAINT `fk_asocasoveh` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehiasoveh` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoacta`
--
ALTER TABLE `coddocumprocesoacta`
  ADD CONSTRAINT `fk_codoprcodopa` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipactcodopa` FOREIGN KEY (`tipactid`) REFERENCES `tipoacta` (`tipactid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopa` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoanexo`
--
ALTER TABLE `coddocumprocesoanexo`
  ADD CONSTRAINT `fk_codoprcodopx` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocambioestado`
--
ALTER TABLE `coddocumprocesocambioestado`
  ADD CONSTRAINT `fk_codoprcodpce` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesdocodpce` FOREIGN KEY (`tiesdoid`) REFERENCES `tipoestadodocumento` (`tiesdoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodpce` FOREIGN KEY (`codpceusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocertificado`
--
ALTER TABLE `coddocumprocesocertificado`
  ADD CONSTRAINT `fk_codoprcodopc` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tippedocodopc` FOREIGN KEY (`tipedoid`) REFERENCES `tipopersonadocumental` (`tipedoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopc` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocircular`
--
ALTER TABLE `coddocumprocesocircular`
  ADD CONSTRAINT `fk_codoprcodopl` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdescodopl` FOREIGN KEY (`tipdesid`) REFERENCES `tipodespedida` (`tipdesid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopl` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocitacion`
--
ALTER TABLE `coddocumprocesocitacion`
  ADD CONSTRAINT `fk_codoprcodopt` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipactcodopt` FOREIGN KEY (`tipactid`) REFERENCES `tipoacta` (`tipactid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopt` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocompartido`
--
ALTER TABLE `coddocumprocesocompartido`
  ADD CONSTRAINT `fk_codoprcodopd` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopd` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoconstancia`
--
ALTER TABLE `coddocumprocesoconstancia`
  ADD CONSTRAINT `fk_codoprcodopn` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tippedocodopn` FOREIGN KEY (`tipedoid`) REFERENCES `tipopersonadocumental` (`tipedoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopn` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocopia`
--
ALTER TABLE `coddocumprocesocopia`
  ADD CONSTRAINT `fk_codoprcodopp` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depecodopp` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesofirma`
--
ALTER TABLE `coddocumprocesofirma`
  ADD CONSTRAINT `fk_carlabcodopf` FOREIGN KEY (`carlabid`) REFERENCES `cargolaboral` (`carlabid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_codoprcodopf` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perscodopf` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesooficio`
--
ALTER TABLE `coddocumprocesooficio`
  ADD CONSTRAINT `fk_codoprcodopo` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdescodopo` FOREIGN KEY (`tipdesid`) REFERENCES `tipodespedida` (`tipdesid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipsalcodopo` FOREIGN KEY (`tipsalid`) REFERENCES `tiposaludo` (`tipsalid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopo` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoraddocentrante`
--
ALTER TABLE `coddocumprocesoraddocentrante`
  ADD CONSTRAINT `fk_codoprcdprde` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_radoencdprde` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `codigodocumental`
--
ALTER TABLE `codigodocumental`
  ADD CONSTRAINT `fk_coddocuser` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depecoddoc` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serdoccoddoc` FOREIGN KEY (`serdocid`) REFERENCES `seriedocumental` (`serdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_susedocoddoc` FOREIGN KEY (`susedoid`) REFERENCES `subseriedocumental` (`susedoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdepcoddoc` FOREIGN KEY (`tipdetid`) REFERENCES `tipodestino` (`tipdetid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdoccoddoc` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipmedcoddoc` FOREIGN KEY (`tipmedid`) REFERENCES `tipomedio` (`tipmedid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiptracoddoc` FOREIGN KEY (`tiptraid`) REFERENCES `tipotramite` (`tiptraid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `codigodocumentalproceso`
--
ALTER TABLE `codigodocumentalproceso`
  ADD CONSTRAINT `fk_coddoccodopr` FOREIGN KEY (`coddocid`) REFERENCES `codigodocumental` (`coddocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesdocodopr` FOREIGN KEY (`tiesdoid`) REFERENCES `tipoestadodocumento` (`tiesdoid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `colocacion`
--
ALTER TABLE `colocacion`
  ADD CONSTRAINT `fk_solcrecolo` FOREIGN KEY (`solcreid`) REFERENCES `solicitudcredito` (`solcreid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesclcolo` FOREIGN KEY (`tiesclid`) REFERENCES `tipoestadocolocacion` (`tiesclid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacolo` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `colocacioncambioestado`
--
ALTER TABLE `colocacioncambioestado`
  ADD CONSTRAINT `fk_colococaes` FOREIGN KEY (`coloid`) REFERENCES `colocacion` (`coloid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesclcocaes` FOREIGN KEY (`tiesclid`) REFERENCES `tipoestadocolocacion` (`tiesclid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaclcaes` FOREIGN KEY (`cocaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `colocacionliquidacion`
--
ALTER TABLE `colocacionliquidacion`
  ADD CONSTRAINT `fk_colocolliq` FOREIGN KEY (`coloid`) REFERENCES `colocacion` (`coloid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD CONSTRAINT `fk_agencond` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perscond` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiescocond` FOREIGN KEY (`tiescoid`) REFERENCES `tipoestadoconductor` (`tiescoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipconcond` FOREIGN KEY (`tipconid`) REFERENCES `tipoconductor` (`tipconid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductorcambioestado`
--
ALTER TABLE `conductorcambioestado`
  ADD CONSTRAINT `fk_condcocaes` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesascocaes` FOREIGN KEY (`tiescoid`) REFERENCES `tipoestadoconductor` (`tiescoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacocaes` FOREIGN KEY (`cocaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductorlicencia`
--
ALTER TABLE `conductorlicencia`
  ADD CONSTRAINT `fk_condconlic` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticaliconlic` FOREIGN KEY (`ticaliid`) REFERENCES `tipocategorialicencia` (`ticaliid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductorvehiculo`
--
ALTER TABLE `conductorvehiculo`
  ADD CONSTRAINT `fk_condconveh` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehiconveh` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD CONSTRAINT `fk_persdepe` FOREIGN KEY (`depejefeid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  ADD CONSTRAINT `fk_depedepper` FOREIGN KEY (`depperdepeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_persdepper` FOREIGN KEY (`depperpersid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  ADD CONSTRAINT `fk_depedesusd` FOREIGN KEY (`desusddepeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_susedodesusd` FOREIGN KEY (`desusdsusedoid`) REFERENCES `subseriedocumental` (`susedoid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `fk_depaempr` FOREIGN KEY (`emprdepaid`) REFERENCES `departamento` (`depaid`),
  ADD CONSTRAINT `fk_muniempr` FOREIGN KEY (`emprmuniid`) REFERENCES `municipio` (`muniid`),
  ADD CONSTRAINT `fk_persempr` FOREIGN KEY (`persidrepresentantelegal`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  ADD CONSTRAINT `fk_modufunc` FOREIGN KEY (`moduid`) REFERENCES `modulo` (`moduid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  ADD CONSTRAINT `fk_usuahiscon` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  ADD CONSTRAINT `fk_usuaingsis` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `fk_depamuni` FOREIGN KEY (`munidepaid`) REFERENCES `departamento` (`depaid`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_carglabpers` FOREIGN KEY (`carlabid`) REFERENCES `cargolaboral` (`carlabid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depapersexp` FOREIGN KEY (`persdepaidexpedicion`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depapersnac` FOREIGN KEY (`persdepaidnacimiento`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munipersexp` FOREIGN KEY (`persmuniidexpedicion`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munipersnac` FOREIGN KEY (`persmuniidnacimiento`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipidepers` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipperpers` FOREIGN KEY (`tipperid`) REFERENCES `tipopersona` (`tipperid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  ADD CONSTRAINT `fk_tipideperado` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  ADD CONSTRAINT `fk_radoenradoea` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  ADD CONSTRAINT `fk_radoenradece` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tierderadece` FOREIGN KEY (`tierdeid`) REFERENCES `tipoestadoraddocentrante` (`tierdeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaradece` FOREIGN KEY (`radeceusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocentdependencia`
--
ALTER TABLE `radicaciondocentdependencia`
  ADD CONSTRAINT `fk_deperadoed` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_radoenradoed` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaradoed` FOREIGN KEY (`radoedsuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocumentoentrante`
--
ALTER TABLE `radicaciondocumentoentrante`
  ADD CONSTRAINT `fk_deparadoen` FOREIGN KEY (`depaid`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_muniradoen` FOREIGN KEY (`muniid`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peradoradoen` FOREIGN KEY (`peradoid`) REFERENCES `personaradicadocumento` (`peradoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_radoen` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tierderadoen` FOREIGN KEY (`tierdeid`) REFERENCES `tipoestadoraddocentrante` (`tierdeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipmedradoen` FOREIGN KEY (`tipmedid`) REFERENCES `tipomedio` (`tipmedid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `rolfuncionalidad`
--
ALTER TABLE `rolfuncionalidad`
  ADD CONSTRAINT `fk_funcrolfun` FOREIGN KEY (`rolfunfuncid`) REFERENCES `funcionalidad` (`funcid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rolrolfun` FOREIGN KEY (`rolfunrolid`) REFERENCES `rol` (`rolid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudcredito`
--
ALTER TABLE `solicitudcredito`
  ADD CONSTRAINT `fk_asocsolcre` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lincresolcre` FOREIGN KEY (`lincreid`) REFERENCES `lineacredito` (`lincreid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesscsolcre` FOREIGN KEY (`tiesscid`) REFERENCES `tipoestadosolicitudcredito` (`tiesscid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuasolcre` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehisolcre` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudcreditocambioestado`
--
ALTER TABLE `solicitudcreditocambioestado`
  ADD CONSTRAINT `fk_solcresocrce` FOREIGN KEY (`solcreid`) REFERENCES `solicitudcredito` (`solcreid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesscsocrce` FOREIGN KEY (`tiesscid`) REFERENCES `tipoestadosolicitudcredito` (`tiesscid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuasocrce` FOREIGN KEY (`socrceusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  ADD CONSTRAINT `fk_serdocsusedo` FOREIGN KEY (`serdocid`) REFERENCES `seriedocumental` (`serdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdocsusedo` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  ADD CONSTRAINT `fk_perstofipe` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_persusua` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD CONSTRAINT `fk_rolusurol` FOREIGN KEY (`usurolrolid`) REFERENCES `rol` (`rolid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuausurol` FOREIGN KEY (`usurolusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `fk_agenvehi` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticavevehi` FOREIGN KEY (`ticaveid`) REFERENCES `tipocarroceriavehiculo` (`ticaveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticovevehi` FOREIGN KEY (`ticoveid`) REFERENCES `tipocolorvehiculo` (`ticoveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticovhvehi` FOREIGN KEY (`ticovhid`) REFERENCES `tipocombustiblevehiculo` (`ticovhid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesvevehi` FOREIGN KEY (`tiesveid`) REFERENCES `tipoestadovehiculo` (`tiesveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_timavevehi` FOREIGN KEY (`timaveid`) REFERENCES `tipomarcavehiculo` (`timaveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_timovevehi` FOREIGN KEY (`timoveid`) REFERENCES `tipomodalidadvehiculo` (`timoveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipvehvehi` FOREIGN KEY (`tipvehid`) REFERENCES `tipovehiculo` (`tipvehid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tirevevehi` FOREIGN KEY (`tireveid`) REFERENCES `tiporeferenciavehiculo` (`tireveid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculocambioestado`
--
ALTER TABLE `vehiculocambioestado`
  ADD CONSTRAINT `fk_tiesvevecaes` FOREIGN KEY (`tiesveid`) REFERENCES `tipoestadovehiculo` (`tiesveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuavecaes` FOREIGN KEY (`vecaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivecaes` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculocrt`
--
ALTER TABLE `vehiculocrt`
  ADD CONSTRAINT `fk_vehivehcrt` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculopoliza`
--
ALTER TABLE `vehiculopoliza`
  ADD CONSTRAINT `fk_vehivehpol` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculosoat`
--
ALTER TABLE `vehiculosoat`
  ADD CONSTRAINT `fk_vehivehsoa` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculotarjetaoperacion`
--
ALTER TABLE `vehiculotarjetaoperacion`
  ADD CONSTRAINT `fk_tisevevetaop` FOREIGN KEY (`tiseveid`) REFERENCES `tiposerviciovehiculo` (`tiseveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivetaop` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
