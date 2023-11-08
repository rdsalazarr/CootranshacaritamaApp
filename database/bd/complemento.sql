ALTER TABLE `empresa` ADD `emprpersoneriajuridica` VARCHAR(50) NULL COMMENT 'Personería jurídica de la empresa' AFTER `emprcorreo`;
UPDATE `empresa` SET `emprpersoneriajuridica` = 'Personería Jurídica No. 73 Enero 28/1976' WHERE `empresa`.`emprid` = 1;



CREATE TABLE `informaciongeneralpdf` (
  `ingpdfid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla información general PDF',
  `ingpdfnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre general para utilizar la consulta de la información en PDF',
  `ingpdftitulo` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título de la información general del PDF',
  `ingpdfcontenido` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contenido de la información que lleva PDF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informaciongeneralpdf`
--

INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(1, 'contratoVehiculo', 'CONTRATO DE VINCULACIÓN CNT-numeroContrato', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Entre los suscritos a saber <strong>nombreGerente</strong>, mayor de edad, vecino de Oca&ntilde;a, identificado con tpDocumentoGerente documentoGerente de ciudadExpDocumentoGerente, quien obra en calidad de Gerente y representante legal de la Cooperativa de Transportadores Hacaritama, Cootranshacaritama, por una parte y que en adelante se llamar&aacute; LA COOPERATIVA y&nbsp;<strong>nombreAsociado&nbsp;</strong>identificado con tpDocumentoAsociado. documentoAsociado de ciudadExpDocumentoAsociado mayor de edad y vecino de OCA&Ntilde;A, por otra parte, y que en adelante se llamar&aacute; ASOCIADO, han celebrado un contrato de vinculaci&oacute;n para cumplir con el Art&iacute;culo 19 de los Estatuto que rigen a LA COOPERATIVA.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: center;\"><strong>CLAUSULAS</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: left;\"><strong>PRIMERA:</strong> El ASOCIADO presenta el siguiente veh&iacute;culo:</p>\r\n<table style=\"border-collapse: collapse; width: 68.381%; border-width: 1px; height: 189.938px;\" border=\"1\"><colgroup><col style=\"width: 47.6286%;\"><col style=\"width: 52.4229%;\"></colgroup>\r\n<tbody>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"background-color: rgb(235, 235, 235); height: 19.5938px; text-align: center;\" colspan=\"2\"><strong>DETALLE DEL VEH&Iacute;CULO</strong></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>PLACA</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">placaVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>N&Uacute;MERO INTERNO</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">numeroInternoVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 13.5938px;\">\r\n<td style=\"height: 13.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>CLASE &nbsp;</strong></span></td>\r\n<td style=\"height: 13.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">claseVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>CILINDRAJE</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">cilindrajeVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>TIPO DE CARROCER&Iacute;A</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">carroceriaVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>MODELO</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">modeloVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>MARCA &nbsp; &nbsp;</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">marcaVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>COLOR &nbsp; &nbsp;</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">colorVehiculo</span></td>\r\n</tr>\r\n<tr style=\"height: 19.5938px;\">\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\"><strong>CAPACIDAD &nbsp; &nbsp;</strong></span></td>\r\n<td style=\"height: 19.5938px;\"><span lang=\"ES\" style=\"font-family: \'Arial\',sans-serif; mso-bidi-font-family: \'Arial MT\';\">capacidadVehiculo</span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>El cual se declara libre de acci&oacute;n legal, pleitos pendientes, embargos judiciales, condiciones resolutorias y en general, cualquier otro gravamen. <strong>SEGUNDA</strong>: La duraci&oacute;n del presente contrato es por el t&eacute;rmino de un (1) a&ntilde;o a partir de su perfeccionamiento, dicho contrato no se prorroga de forma autom&aacute;tica, por lo que no es necesario dar preaviso a las partes con anterioridad a su vencimiento. <strong>TERCERA</strong>: El valor a pagar por la suscripci&oacute;n del presente contrato ser&aacute; lo acordado por los estatutos, acuerdos y reglamentos vigentes. <strong>CUARTA</strong>: La COOPERATIVA se obliga a colocar y mantener el plan de rodamiento que para este tipo de veh&iacute;culo le ha se&ntilde;alado el Ministerio de Transporte y/o autoridad competente. <strong>QUINTA</strong>: Los impuestos del veh&iacute;culo, multas, da&ntilde;os a terceros en caso de accidentes, servicios m&eacute;dicos, farmac&eacute;uticos, quir&uacute;rgicos, hospitalarios y dem&aacute;s que se ocasionen por el veh&iacute;culo, gastos de combustibles, dineros entregados, prestaciones sociales, salarios e indemnizaciones, seguros del conductor, entre otros, ser&aacute;n de cuenta exclusiva del asociado propietario del veh&iacute;culo. <strong>SEXTA</strong>: El ASOCIADO se compromete a cancelar en la planilla (cuota administrativa) el valor por concepto de servicios administrativos m&aacute;s el aporte social de conformidad con los estatutos vigentes de la cooperativa. <strong>S&Eacute;PTIMA</strong>: El ASOCIADO se responsabiliza de todas y cada una de las prestaciones sociales de sus trabajadores, manteniendo indemne a la COOPERATIVA de cualquier demanda, denuncia, queja o reclamo, teniendo en cuenta la relaci&oacute;n laboral es &uacute;nica y exclusiva entre el ASOCIADO y el CONDUCTOR del veh&iacute;culo vinculado. <strong>OCTAVA</strong>: El ASOCIADO ser&aacute; el &uacute;nico responsable, indemnizar&aacute; y mantendr&aacute; a la COOPERATIVA indemne y libre de todo tipo de P&eacute;rdidas causadas a la COOPERATIVA, a los ASOCIADOS, al Personal, a Otros ASOCIADOS y/o a terceras.<span style=\"mso-spacerun: yes;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Personas, que sean imputables a los actos u omisiones del ASOCIADO, sus trabajadores y/o el Personal, o que se produzcan como consecuencia del incumplimiento del Contrato, de un Servicio, de los Permisos y/o de las Leyes Aplicables, pudiendo la COOPERATIVA cobrar, compensar o deducir cualquier P&eacute;rdida contra las sumas adeudadas o que lleguen a adeudarse al ASOCIADO bajo cualquier pago pendiente. <strong>NOVENA</strong>:&nbsp; En particular, y sin que ello implique limitaci&oacute;n alguna de lo previsto en la cl&aacute;usula anterior, el ASOCIADO ser&aacute; responsable y mantendr&aacute; al COOPERATIVA indemne frente a todo tipo de P&eacute;rdidas por: (i) cualquier incumplimiento de las Leyes Aplicables, los Permisos y/o de las obligaciones derivadas de actos administrativos expedidos por las Autoridades Competentes, y/o por cualquier afectaci&oacute;n o da&ntilde;o; (ii) P&eacute;rdidas relacionadas con Impuestos que den lugar a un proceso de fiscalizaci&oacute;n o reclamaci&oacute;n de cualquier tipo por parte de las Autoridades Competentes tributarias nacionales o locales, relacionadas con la ejecuci&oacute;n de este Contrato; (iii) cualquier sanci&oacute;n o condena impuesta por las Autoridades Competentes administrativas o judiciales en relaci&oacute;n con el incumplimiento de las obligaciones laborales y de seguridad social del ASOCIADO y sus trabajadores, as&iacute; como por cualquier reclamaci&oacute;n judicial o administrativa iniciada por el trabajador a cargo del ASOCIADO asignado a la ejecuci&oacute;n del Servicio, o por los causahabientes de dicho Personal. <strong>DECIMA</strong>: Las obligaciones de indemnidad del ASOCIADO frente a la COOPERATIVA estar&aacute;n sujetas a los mismos t&eacute;rminos que aquellos aplicables a la prescripci&oacute;n de las acciones correspondientes seg&uacute;n el tipo de reclamaci&oacute;n de que se trate. No obstante, en el evento en que, con posterioridad al vencimiento del correspondiente t&eacute;rmino de prescripci&oacute;n, la COOPERATIVA sea notificada acerca de reclamaciones por P&eacute;rdidas que hayan sido presentadas con anterioridad a dicho vencimiento por terceras Personas (incluyendo el Personal y las Autoridades Competentes), as&iacute; como por reclamaciones laborales a cargo del ASOCIADO, la COOPERATIVA tendr&aacute; un (1) a&ntilde;o m&aacute;s a partir de la fecha de vencimiento del respectivo t&eacute;rmino de prescripci&oacute;n para presentar al ASOCIADO una reclamaci&oacute;n bajo esta cl&aacute;usula con base en dichas reclamaciones. <strong>DECIMA PRIMERA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA SEGUNDA</strong>: Ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes aprobados el 10 de marzo de 2019, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA TERCERA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA CUARTA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA QUINTA</strong>: ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos, en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA SEXTA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA S&Eacute;PTIMA</strong>: El ASOCIADO se compromete con la COOPERATIVA a dar aviso inmediato de los cambios de direcci&oacute;n e informaci&oacute;n personal. <strong>DECIMA OCTAVA</strong>: Se aclara que la venta del veh&iacute;culo a terceros, no implica para el asociado vendedor la cesi&oacute;n de sus aportes sociales, ni dem&aacute;s compromisos econ&oacute;micos que pueda llegar a tener. Tampoco implica la venta la a capacidad transportadora, pues solamente el asociado tiene pleno dominio sobre el veh&iacute;culo. <strong>DECIMA NOVENA</strong>: Quien se constituya en nuevo propietario, debe asociarse a la cooperativa de inmediato para tener derecho a la utilizaci&oacute;n de la capacidad transportadora y se someter&aacute; a los requisitos y tr&aacute;mites de ingresos a la COOPERATIVA como nuevo asociado, reserv&aacute;ndose la COOPERATIVA los derechos de admisi&oacute;n. La capacidad transportadora ser&aacute; siempre de la COOPERATIVA quien tiene la postad de poderla asignar de forma temporal a quienes cumplan con los requisitos para ser asociado. <strong>VIG&Eacute;SIMA</strong>: el presente contrato de vinculaci&oacute;n tiene una duraci&oacute;n de un a&ntilde;o contados a partir de la firma del mismo, no se aplicar&aacute;n prorrogas ni adiciones, adem&aacute;s no es necesario preavisar la terminaci&oacute;n del mismo ya que esta se entiende comunicada y a satisfacci&oacute;n con la firma del presente contrato.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br><strong>FECHA</strong>: fechaContrato<br><strong>DIRECCION</strong>: direccionAsociado<br><strong>TELEFONO(S)</strong>: telefonoAsociado<br><strong>DOCUMENTOS ADICIONALES</strong>: documentosAdionales<br><strong>OBSERVACIONES</strong>: observacionGeneral</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><strong>nombreAsociado </strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreGerente</strong><br>Asociado&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Gerente</p>\r\n<p class=\"MsoNormal\">&nbsp;</p>\r\n<p class=\"MsoBodyText\" style=\"margin-left: 5.5pt; text-align: justify;\">&nbsp;</p>', '2023-10-20 14:45:52', '2023-10-20 20:00:31'),
(2, 'pagareColocacion', 'PAGARÉ NÚMERO  numeroPagare', '<table style=\"border-collapse: collapse; width: 100.008%;\" border=\"0\"><colgroup><col style=\"width: 27.3991%;\"><col style=\"width: 25.5309%;\"><col style=\"width: 25.5303%;\"><col style=\"width: 21.5878%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td>N&uacute;mero de pagar&eacute;:</td>\r\n<td><strong>numeroPagare</strong></td>\r\n<td>Valor del cr&eacute;dito:</td>\r\n<td>$ <strong>valorCredito</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Fecha de la solicitud:</td>\r\n<td>fechaSolicitud</td>\r\n<td>Fecha del desembolso:</td>\r\n<td><strong>fechaDesembolso</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Fecha de la primera cuota:</td>\r\n<td>fechaPrimeraCuota</td>\r\n<td>Fecha de la &uacute;ltima cuota:</td>\r\n<td>fechaUltimaCuota</td>\r\n</tr>\r\n<tr>\r\n<td>Inter&eacute;s mensual:</td>\r\n<td>interesMensual %</td>\r\n<td>N&uacute;mero de cuotas:</td>\r\n<td>numeroCuota</td>\r\n</tr>\r\n<tr>\r\n<td>Destinaci&oacute;n del cr&eacute;dito:</td>\r\n<td colspan=\"3\">destinacionCredito</td>\r\n</tr>\r\n<tr>\r\n<td>Referencia:</td>\r\n<td>referenciaCredito</td>\r\n<td>Garant&iacute;a:</td>\r\n<td>garantiaCredito</td>\r\n</tr>\r\n<tr>\r\n<td>N&uacute;mero interno:</td>\r\n<td>numeroInternoVehiculo</td>\r\n<td>Placa:</td>\r\n<td>placaVehiculo</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">El Suscrito, <strong>nombreAsociado </strong>identificado con tpDocumentoAsociado <strong>documentoAsociado</strong>, deudor(a) principal me obligo a pagar solidaria e incondicionalmente en dinero en efectivo a COOTRANSHACARITAMA LTDA, en su oficina de Oca&ntilde;a N.S, a su orden o a quien represente sus derechos, la suma de ($ <strong>valorCredito</strong>), (<strong>valorEnLetras</strong>) moneda legal recibida en calidad de mutuo o pr&eacute;stamo a inter&eacute;s. INTERESES: Que sobre la suma debida reconocer&eacute; intereses equivalentes al <strong>interesMensual</strong>% mensual, sobre el saldo de capital insoluto, los cuales se liquidar&aacute;n y pagar&aacute;n mes vencido, junto con la cuota mensual correspondiente al mes de causaci&oacute;n. En caso de mora, reconocer&eacute; intereses moratorios del <strong>interesMoratorio</strong>% mensual. PARAGRAFO: En caso que la tasa de inter&eacute;s corriente y/o moratorio pactado, sobrepase los topes m&aacute;ximos permitidos por las disposiciones comerciales, dichas tasas se ajustar&aacute;n mensualmente a los m&aacute;ximos legales. PLAZO: Que pagar&eacute; la suma indicada en la cl&aacute;usula anterior mediante instalamentos mensuales sucesivos y en <strong>numeroCuota </strong>cuota(s), correspondientes cada una a la cantidad de $ <strong>valorCuota</strong>,&nbsp; m&aacute;s los intereses corrientes sobre el saldo, a partir del d&iacute;a fechaLargaPrestamo. VENCIMIENTO DEL PLAZO: Autorizo a COOTRANSHACARITAMA LTDA para declarar vencido totalmente el plazo de esta obligaci&oacute;n y exigir el pago inmediato del saldo, intereses, gastos judiciales y de los que se causen por el cobro de la obligaci&oacute;n, en cualquiera de los siguientes casos: a) Por mora de una o m&aacute;s cuotas de capital o de los intereses de esta o cualquier obligaci&oacute;n que, conjunta o separadamente, tenga contra&iacute;da a favor de COOTRANSHACARITAMA LTDA ; b) Si fuere demandado judicialmente o si los bienes de cualquiera de los otorgantes son embargados o perseguidos por la v&iacute;a judicial; c) Por muerte, concordato, quiebra, concurso de acreedores, disoluci&oacute;n, liquidaci&oacute;n o inhabilidad de uno de los otorgantes; d) Si mis activos se disminuyen, los bienes dados en garant&iacute;a se gravan o enajenan en todo o en parte o dejan de ser respaldo suficiente de la(s) obligaci&oacute;n(es) adquirida(s) o si incumpliera la obligaci&oacute;n de mantener actualizada la garant&iacute;a; e) Si la inversi&oacute;n del cr&eacute;dito fuese diferente de la convenida o de la mencionada en la solicitud del pr&eacute;stamo; f) si no actualizo(amos) oportunamente la informaci&oacute;n legal y financiera en los plazos que determine COOTRANSHACARITAMA LTDA; g) Las dem&aacute;s que las reglamentaciones internas de COOTRANSHACARITAMA LTDA contemplen. GASTOS E IMPUESTOS: Todos los gastos e impuestos que cause este pagar&eacute; sean de mi cargo, as&iacute; como los honorarios de abogado, costos judiciales y dem&aacute;s gastos que se generen. Me oblig&oacute; a cancelar las primas de seguros en las condiciones establecidas en las p&oacute;lizas respectivas. Autorizo a COOTRANSHACARITAMA LTDA para debitar de la(s) cuenta(s) de dep&oacute;sito(s) en todas las modalidades que tenga cualquiera de los otorgantes, el importe de este t&iacute;tulo valor, la cuota o cuotas respectivas, los intereses, primas de seguros y dem&aacute;s gastos o impuestos causados por esta obligaci&oacute;n. DESCUENTOS LABORALES: De acuerdo con lo previsto en el art&iacute;culo 142 de la ley 79 de 1988, autorizo (amos) irrevocablemente a la persona natural o jur&iacute;dica, p&uacute;blica o privada, a quien corresponda realizarme el pago de cualquier cantidad de dinero por concepto laboral o prestaciones, para que deduzca o retenga de estos valores, sin perjuicio de las acciones judiciales que quiera iniciar directamente sin hacer valer la autorizaci&oacute;n. Se suscribe en la ciudad de Oca&ntilde;a a los fechaLargaDesembolso.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2023-10-20 20:10:43', '2023-10-20 21:56:58'),
(3, 'cartaInstrucciones', 'REFERENCIA:    CARTA DE INSTRUCCIONES', '<p style=\"text-align: justify;\">Yo, <strong>nombreAsociado &nbsp;</strong>mayor de edad, identificado como aparece al pie de mi firma, actuando en nombre propio, por medio del presente escrito manifiesto que le faculto a usted, de manera permanente e irrevocable para que, en caso de incumplimiento en el pago oportuno de alguna de las obligaciones que hemos adquirido con usted, derivadas de los negocios comerciales y contractuales bien sean verbales o escritos; sin previo aviso, proceda a llenar los espacios en blanco La letra del pagar&eacute; No. 55541 que he suscrito en la fecha a su favor y que se anexa, con el fin de convertir el pagar&eacute;, en un documento que presta m&eacute;rito ejecutivo y que est&aacute; sujeto a los par&aacute;metros legales del Art&iacute;culo 622 del C&oacute;digo de Comercio.</p>\r\n<p style=\"text-align: justify;\">1. El espacio correspondiente a &ldquo;la suma cierta de&rdquo; se llenar&aacute; por una suma igual a la que resulte pendiente de pago de todas la obligaciones contra&iacute;das con el acreedor, por concepto de capital, intereses, seguros, cobranza extrajudicial, seg&uacute;n la contabilidad del acreedor a la fecha en que sea llenado el pagar&eacute;.</p>\r\n<p style=\"text-align: justify;\">2. El espacio correspondiente a la fecha en que se debe hacer el pago, se llenar&aacute; con la fecha correspondiente al d&iacute;a en que sea llenado el pagar&eacute;, fecha que se entiende que es la de su vencimiento.</p>\r\n<p style=\"text-align: justify;\">En constancia de lo anterior firmamos la presente autorizaci&oacute;n, el d&iacute;a fechaLargaPrestamo.</p>\r\n<p style=\"text-align: justify;\">EL DEUDOR,</p>', '2023-10-20 22:03:54', '2023-10-20 22:03:54');

/*
INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(38, 8, 'Cobranza', 'Getionar cobranza', 'admin/cartera/cobranza', 'table_chart_icon', 6, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(38, 1, 38);

UPDATE `funcionalidad` SET `funcnombre` = 'Historial S.C.', `functitulo` = 'Getionar Historial', `funcruta` = 'admin/cartera/historial', `funcicono` = 'auto_stories_icon' WHERE `funcionalidad`.`funcid` = 37;


INSERT INTO `tipoestadosolicitudcredito` (`tiesscid`, `tiesscnombre`) VALUES ('D', 'Desembolsado');
*/

/*ALTER TABLE `vehiculo` ADD `vehiobservacion` VARCHAR(500) NULL COMMENT 'Observación general del vehículo' AFTER `vehiesserieregrabado`;*/