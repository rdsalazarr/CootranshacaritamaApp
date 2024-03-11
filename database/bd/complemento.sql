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


INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(5, 'fichaTecnica', 'FICHA TÉCNICA', '<p class=\"MsoNormal\"><strong>FICHA T&Eacute;CNICA</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">FORMATO UNICO DE EXTRACTO DEL CONTRATO \"FUEC\" REVERSO</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">INSTRUCTIVO PARA LA DETERMINACI&Oacute;N DEL N&Uacute;MERO CONSECUTIVO DEL FUEC</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">El formato &Uacute;nico de Extracto del Contrato \"FUEC\" estar&aacute; constituida por los siguientes n&uacute;meros:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">a) Los tres primeros d&iacute;gitos de izquierda a derecha corresponder&aacute;n al c&oacute;digo de la Direcci&oacute;n Territorial que otorg&oacute; la habilitaci&oacute;n de la empresa de transporte de Servicio Especial.</p>\r\n<table style=\"border-collapse: collapse; width: 90%;\" border=\"1\"><colgroup><col style=\"width: 35%;\"><col style=\"width: 15%;\"><col style=\"width: 35%;\"><col style=\"width: 15%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 40%;\"><strong>Antioquia - Choc&oacute;</strong></td>\r\n<td style=\"width: 10%; text-align: center;\"><strong>305</strong></td>\r\n<td style=\"width: 40%;\"><strong>Huila - Caquet&aacute;</strong></td>\r\n<td style=\"width: 10%; text-align: center;\"><strong>441</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Atl&aacute;ntico</strong></td>\r\n<td style=\"text-align: center;\"><strong>208</strong></td>\r\n<td><strong>Magdalena</strong></td>\r\n<td style=\"text-align: center;\"><strong>247</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Bolivar - San Andr&eacute;s y Providencia</strong></td>\r\n<td style=\"text-align: center;\"><strong>213</strong></td>\r\n<td><strong>Meta - Vaup&eacute;s - Vichada</strong></td>\r\n<td style=\"text-align: center;\"><strong>550</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Boyac&aacute; - Casanare</strong></td>\r\n<td style=\"text-align: center;\"><strong>415</strong></td>\r\n<td><strong>Nari&ntilde;o - Putumayo</strong></td>\r\n<td style=\"text-align: center;\"><strong>352</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Caldas</strong></td>\r\n<td style=\"text-align: center;\"><strong>317</strong></td>\r\n<td><strong>N/Santander - Arauca</strong></td>\r\n<td style=\"text-align: center;\"><strong>454</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Cauca</strong></td>\r\n<td style=\"text-align: center;\"><strong>319</strong></td>\r\n<td><strong>Quind&iacute;o</strong></td>\r\n<td style=\"text-align: center;\"><strong>363</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Cesar</strong></td>\r\n<td style=\"text-align: center;\"><strong>220</strong></td>\r\n<td><strong>Risaralda</strong></td>\r\n<td style=\"text-align: center;\"><strong>366</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>C&oacute;rdoba - Sucre</strong></td>\r\n<td style=\"text-align: center;\"><strong>223</strong></td>\r\n<td><strong>Santander</strong></td>\r\n<td style=\"text-align: center;\"><strong>468</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Cundinamarca</strong></td>\r\n<td style=\"text-align: center;\"><strong>425</strong></td>\r\n<td><strong>Tolima</strong></td>\r\n<td style=\"text-align: center;\"><strong>473</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Guajira</strong></td>\r\n<td style=\"text-align: center;\"><strong>241</strong></td>\r\n<td><strong>Valle del Cauca</strong></td>\r\n<td style=\"text-align: center;\"><strong>376</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">b) Los cuatro d&iacute;gitos siguientes se&ntilde;alar&aacute;n el n&uacute;mero de resoluci&oacute;n mediante el cual se otorg&oacute; la habilitaci&oacute;n de la Empresa. En caso que la resoluci&oacute;n no tenga estos d&iacute;gitos, los faltantes ser&aacute;n completados con ceros a la izquierda.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">c) Los dos siguientes d&iacute;gitos corresponder&aacute;n a los dos &uacute;ltimos del a&ntilde;o en que la empresa fue habilitada.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">d) A continuaci&oacute;n, cuatro d&iacute;gitos que corresponder&aacute;n al a&ntilde;o en que se expide el extracto del contrato.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">e) Posteriormente, cuatro d&iacute;gitos que identifican el n&uacute;mero del contrato. La numeraci&oacute;n debe ser consecutiva, establecida por cada empresa y continuar&aacute; con la numeraci&oacute;n dada a los contratos de prestaci&oacute;n de servicio celebrados para el transporte de estudiantes, empleados, turistas, usuarios del servicio de salud y grupos espec&iacute;ficos de usuarios, en vigencia de la resoluci&oacute;n 3068 de 2014.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">f) Finalmente, los cuatro &uacute;ltimos d&iacute;gitos corresponder&aacute;n al n&uacute;mero consecutivo o identificaci&oacute;n interna del extracto de contrato que se expida, para la ejecuci&oacute;n de cada contrato. Se debe expedir un nuevo extracto por vencimiento del plazo inicial del mismo o por cada cambio de veh&iacute;culo.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Ejemplo:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Empresa habilitada por la Direcci&oacute;n Territorial Norte de Santander en el a&ntilde;o 2002, con resoluci&oacute;n de habilitaci&oacute;n N. 0083 que expide el primer extracto del contrato en el a&ntilde;o 2023, del contrato 0693. El n&uacute;mero del Formato &Uacute;nico de Extracto del Contrato \"FUE\" ser&aacute;: numeroContratoServicioEspecial.</p>', '2023-11-09 16:37:16', '2023-11-09 20:12:21'),
(6, 'contratoTransporteEspecial', 'CONTRATO DE TRANSPORTE ESPECIAL numeroContratoServicioEspecial', '<p style=\"text-align: justify;\">Entre los suscritos nombreGerente, identificado con C.C. documentoGerente de Oca&ntilde;a, quien obra en representaci&oacute;n legal de COOPERATIVA DE TRANSPORTADORES HACARITAMA con NIT: 890.505.424-7, domiciliado en Oca&ntilde;a y quien para efectos del presente contrato se llamar&aacute; EL CONTRATISTA y por otra parte nombreContratante identificado(a) con NIT/C.C documentoContratante y quien para el presente contrato se denominar&aacute; EL CONTRATANTE, hemos celebrado el presente contrato que consta de las siguientes cl&aacute;usulas: <strong>PRIMERA</strong>: EL CONTRATISTA se compromete a poner a disposici&oacute;n de EL CONTRATANTE DOS (2) veh&iacute;culo(s) con n&uacute;mero(s) interno(s) 473, 486 con 16, 16 puestos. <strong>SEGUNDA</strong>: OBJETO: objetoContrato. EL CONTRATISTA se compromete a transportar el personal que EL CONTRATANTE le conf&iacute;a en la ruta especificada en la cl&aacute;usula siguiente.&nbsp;<strong>TERCERA</strong>: RUTA: EL CONTRATANTE estipula como ruta la siguiente: Origen: origenContrato, Destino: destinoContrato. <strong>CUARTA</strong>: DIAS CONTRATADOS: El contrato iniciar&aacute; en el origenContrato el d&iacute;a fechaInicialContrato, con destino a destinoContrato hasta el d&iacute;a fechaFinalContrato. <strong>QUINTA</strong>: VALOR: El presente contrato tiene un valor de $valorContrato. Forma de pago: CONTADO.&nbsp;<strong>SEXTA</strong>: El veh&iacute;culo est&aacute; en &oacute;ptimas condiciones de seguridad y mec&aacute;nicas para el transporte del personal en la ruta acordada y porta las p&oacute;lizas contractuales y extracontractuales. <strong>SEPTIMA</strong>: EL CONTRATISTA se compromete a cumplir con los decretos y disposiciones emanados del Ministerio de Transporte, en lo concerniente al transporte de personal contemplados en el Decreto 0348 del 2015, as&iacute; como las dem&aacute;s normas que emita el gobierno para mejorar el transporte.</p>\r\n<p style=\"text-align: justify;\"><br>En constancia firmamos el presente contrato el d&iacute;a fechaInicialContrato.</p>', '2023-11-09 20:17:36', '2023-11-10 15:54:07');


INSERT INTO `modulo` (`moduid`, `modunombre`, `moduicono`, `moduorden`, `moduactivo`, `created_at`, `updated_at`) VALUES
(9, 'Despachos', 'send_time_extension_icon', 9, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(39, 9, 'Servico especial', 'Getionar planilla de servico especial', 'admin/despacho/servicioEspecial', 'taxi_alert_icon', 2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(39, 1, 39);

ALTER TABLE `contratoservicioespecial` ADD `coseesvalorcontrato` VARCHAR(10) NOT NULL AFTER `coseesfechafinal`;
ALTER TABLE `contratoservicioespecial` ADD `coseesnombreuniontemporal` VARCHAR(100) NULL AFTER `coseesdescripcionrecorrido`;



ALTER TABLE `tipomodalidadvehiculo` ADD `timovecuotasotenimiento` VARCHAR(8) NULL AFTER `timovenombre`, ADD `timovedescuentopagoanticipado` VARCHAR(4) NULL AFTER `timovecuotasostenimiento`, ADD `timoverecargomora` VARCHAR(4) NULL AFTER `timovedescuentopagoanticipado`;

ALTER TABLE `tarifatiquete` CHANGE `tartiqfondoreposicion` `tartiqfondoreposicion` DECIMAL(6,2) NOT NULL COMMENT 'Valor para el fondo de reposición del tiquete';
ALTER TABLE `tarifatiquete` CHANGE `tartiqfondoreposicion` `tartiqfondoreposicion` DECIMAL(6,2) NOT NULL COMMENT 'Valor para el fondo de reposición del tiquete';


INSERT INTO municipio (muniid, munidepaid, municodigo, muninombre,created_at,updated_at) VALUES
(1123, 18, '54399', 'Aspacica' ,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1124, 18, '54400', 'La Vega de San Antonio', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1125, 9, '20615', 'Otaré', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1126, 9, '20012', 'Besote', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1127, 9, '20014', 'Casacará', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1128, 18, '54246', 'Guamalito', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1129, 9, '20015', 'Cuatrovientos', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1130, 9, '20016', 'El burro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1131, 9, '20017', 'La loma', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1132, 9, '20018', 'La mata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1133, 9, '20019', 'Rincon Hondo', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


ALTER TABLE `configuracionencomienda` ADD `conencvalorminimodeclarado` DECIMAL(10.0) NULL COMMENT 'Valor mínimo declarado del envío de la encomienda' AFTER `conencvalorminimoenvio`;
UPDATE `configuracionencomienda` SET `conencvalorminimodeclarado` = '10000', `created_at` = NULL, `updated_at` = NULL WHERE `configuracionencomienda`.`conencid` = 1;

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(43, 9, 'Tiquetes', 'Getionar Tiquetes', 'admin/despacho/tiquetes', 'card_travel_icon', 5, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(43, 1, 43);


ALTER TABLE `planillaruta` CHANGE `usuaidrecibe` `usuaiddespacha` SMALLINT(5) UNSIGNED NULL DEFAULT NULL COMMENT 'Identificador del usuario que recibe la planilla';
ALTER TABLE `planillaruta` CHANGE `plarutfechahorarecibe` `plarutfechallegadaaldestino` DATETIME NULL DEFAULT NULL COMMENT 'Fecha y hora actual en que se recibe la planilla para la ruta';

ALTER TABLE `planillaruta` ADD `plarutanio` YEAR NULL AFTER `plarutfechahoraregistro`;

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(44, 9, 'Recibir planilla', 'Getionar proceso de recibir planilla', 'admin/despacho/recibirPlanilla', 'format_align_justify_icon', 5, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(44, 1, 44);


UPDATE `migrations` SET `migration` = '2023_10_18_043322_create_tipo_servicio_vehiculo' WHERE `migrations`.`id` = 79; UPDATE `migrations` SET `migration` = '2023_10_18_043323_create_tipo_vehiculo_distribucion' WHERE `migrations`.`id` = 80;


ALTER TABLE `vehiculoresponsabilidad` CHANGE `vehresfechapago` `vehresfechacompromiso` DATE NOT NULL COMMENT 'Fecha máxima en la cual se debe realizar el pago de la responsabilidad';


INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(45, 6, 'Sancionar', 'Getionar sanciones asociado', 'admin/gestionar/sancionarAsociado', 'person_add_disabled_icon', 3, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(46, 7, 'Suspender', 'Getionar suspención de vehículo', 'admin/direccion/transporte/suspenderVehiculo', 'no_transfer_icon', 5, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(45, 1, 45),
(46, 1, 46);

ALTER TABLE `tipovehiculo` ADD `tipvehclasecss` VARCHAR(50) NOT NULL DEFAULT 'distribucionPuestoGeneral' AFTER `tipvehnumerocolumnas`;

ALTER TABLE `tipovehiculodistribucion` ADD `tivedicontenido` VARCHAR(3) NOT NULL AFTER `tivedinumero`;

UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoTaxi' WHERE tipvehid = 1;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoBus' WHERE tipvehid = 2;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoBus' WHERE tipvehid = 3;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoBus' WHERE tipvehid = 4;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoBus' WHERE tipvehid = 5;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoBus' WHERE tipvehid = 6;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoGeneral' WHERE tipvehid = 7;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoGeneral' WHERE tipvehid = 8;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoGeneral' WHERE tipvehid = 9;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoGeneral' WHERE tipvehid = 10;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoGeneral' WHERE tipvehid = 11;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoGeneral' WHERE tipvehid = 12;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 13;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoTaxi' WHERE tipvehid = 14;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 15;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 16;

UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 17;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 18;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 19;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 20;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 21;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 22;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 23;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 24;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 25;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 26;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 27;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 28;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 29;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBusDos' WHERE tipvehid = 30;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoMicroBus' WHERE tipvehid = 31;
UPDATE tipovehiculo SET tipvehclasecss = 'distribucionPuestoTaxi' WHERE tipvehid = 32;


ALTER TABLE `tipovehiculo` CHANGE `tipvecapacidad` `tipvehcapacidad` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Capacidad del tipo de vehículo', CHANGE `tipvenumerofilas` `tipvehnumerofilas` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Número de filas del tipo de vehículo', CHANGE `tipvenumerocolumnas` `tipvehnumerocolumnas` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Número de columnas del tipo de vehículo', CHANGE `tipveclasecss` `tipvehclasecss` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'distribucionPuestoGeneral' COMMENT 'Clase en CSS para poder visualizar el vehículo con su puesto';


ALTER TABLE `usuario` ADD `cajaid` TINYINT(3) NULL AFTER `agenid`;

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(47, 10, 'Procesar', 'Procesar movimientos de caja', 'admin/caja/procesar', 'currency_exchange_icon', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(48, 10, 'Cerrar', 'Cerrar moviemiento de caja', 'admin/caja/cerrar', 'close_icon', 2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(49, 2, 'Cuenta contable', 'Gestionar cuentas contables', 'admin/gestionar/cuentaContable', 'repeat_one_icon', 8, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(47, 1, 47),
(48, 1, 48),
(49, 1, 49);


ALTER TABLE `vehiculoresponsabilidad` ADD `vehresdescuento` DECIMAL(8,0) NULL AFTER `vehresvalorpagado`, ADD `vehresinteresmora` DECIMAL(8,0) NULL AFTER `vehresdescuento`;
ALTER TABLE `encomienda` CHANGE `encopagada` `encopagocontraentrega` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Determina si la encomienda fue pagada';
ALTER TABLE `encomienda` ADD `encocontabilizada` TINYINT(1) NOT NULL DEFAULT '0' AFTER `encopagocontraentrega`;
ALTER TABLE `personaservicio` ADD `perserpermitenotificacion` TINYINT(1) NOT NULL DEFAULT '0' AFTER `persernumerocelular`;


INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(50, 10, 'Consignar', 'Consignar en bancos', 'admin/caja/consigarBanco', 'price_check_icon', 2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(50, 1, 50);


ALTER TABLE `comprobantecontabledetalle` CHANGE `cocodemonto` `cocodemonto` DOUBLE(12,2) NULL DEFAULT NULL COMMENT 'Monto del movimiento de caja detallado';


INSERT INTO `cuentacontable` (`cueconid`, `cueconcodigo`, `cueconnombre`, `cueconnaturaleza`, `cueconactiva`, `created_at`, `updated_at`) VALUES
(1, '110001', 'CAJA', 'D', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, '110002', 'BANCO', 'D', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, '120003', 'CXP MENSUALIDADES', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, '120004', 'CXP MENSUALIDADES TOTAL', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(5, '120005', 'CXP PAGO CUOTA CRÉDITO', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(6, '120006', 'CXP PAGO CUOTA CRÉDITO TOTAL', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(7, '120007', 'CXP PAGO SANCIÓN', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(8, '120008', 'CXP PAGO ENCOMIENDA', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(9, '120009', 'CXP PAGO ENCOMIENDA CONTRAENTREGA', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(10, '120010', 'CXP PAGO DE TIQUETE', 'C', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(11, '120011', 'CXC DESEMBOLSOS', 'D', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


ALTER TABLE `persona` ADD `perstienefirmaelectronica` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Determina si la persona tiene firma electrónica' AFTER `persrutafirma`;
ALTER TABLE `solicitudcredito` CHANGE `asocid` `persid` INT(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado';

ALTER TABLE `solicitudcredito` CHANGE `vehiid` `vehiid` INT(10) UNSIGNED NULL COMMENT 'Identificador del vehículo';

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(50, 10, 'Consignar', 'Consignar en bancos', 'admin/caja/consigarBanco', 'price_check_icon', 3, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(51, 10, 'Cerrar', 'Cerrar moviemiento de caja', 'admin/caja/cerrar', 'close_icon', 4, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(52, 11, 'Gestionar', 'Gestionar atención al usuario', 'admin/antencion/usuario/gestionar', 'clean_hands_icon', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(50, 1, 50),
(51, 1, 51),
(52, 1, 52);

UPDATE `funcionalidad` SET `funcnombre` = 'Pagar crédito', `functitulo` = 'Pagar desembolso de crédito', `funcruta` = 'admin/caja/pagarDesembolsoCredito', `funcicono` = 'account_balance_icon' WHERE `funcionalidad`.`funcid` = 49;
UPDATE `funcionalidad` SET `funcicono` = 'price_check_icon' WHERE `funcionalidad`.`funcid` = 49; UPDATE `funcionalidad` SET `funcicono` = 'account_balance_icon' WHERE `funcionalidad`.`funcid` = 50;
UPDATE `modulo` SET `modunombre` = 'Atención usuario', `moduicono` = 'repeat_one_icon' WHERE `modulo`.`moduid` = 11;
UPDATE `funcionalidad` SET `funcruta` = 'admin/caja/entregarDesembolsoCredito' WHERE `funcionalidad`.`funcid` = 49;
ALTER TABLE `colocacion` ADD `colocontabilizada` TINYINT(1) NULL DEFAULT '0' COMMENT 'Determina si la colocación ha sido contabilizada' AFTER `colonumerocuota`;
INSERT INTO `cuentacontable` (`cueconid`, `cueconcodigo`, `cueconnombre`, `cueconnaturaleza`, `cueconactiva`, `created_at`, `updated_at`) VALUES ('11', '120011', 'CXC DESEMBOLSOS', 'D', '1', '2024-02-06 09:14:53', '2024-02-06 09:14:53');

ALTER TABLE `tiquete` ADD `tiquvalorestampilla` DECIMAL(10,0) NOT NULL AFTER `tiquvalortotal`, ADD `tiqucontabilizado` TINYINT(1) NOT NULL DEFAULT '0' AFTER `tiquvalorestampilla`;



ALTER TABLE `colocacion` CHANGE `colofechahoraregistro` `colofechahoradesembolso` DATETIME NOT NULL COMMENT 'Fecha y hora actual en el que se registra la colocacion', CHANGE `colofechadesembolso` `colofechacolocacion` DATE NOT NULL COMMENT 'Fecha de desembolso del crédito';
ALTER TABLE `lineacredito` ADD `lincreinteresmora` DECIMAL(6,2) NULL AFTER `lincretasanominal`;
ALTER TABLE `colocacionliquidacion` ADD `colliqvalordescuentoanticipado` DECIMAL(10,0) NULL AFTER `colliqnumerocomprobante`;

UPDATE `migrations` SET `migration` = ' 2023_10_18_053317_create_movimiento_caja' WHERE `migrations`.`id` = 125;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (NULL, '2023_10_18_053317_create_movimiento_caja', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (NULL, '2023_10_18_053318_create_comprobante_contable', '2'); 
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (NULL, '2023_10_18_053319_create_comprobante_contable_detalle', '2');

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(53, 11, 'Verificar', 'Verificar proceso automáticos del dia', 'admin/procesosAutomaticos', 'spellcheck_icon', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(53, 1, 53);

ALTER TABLE `procesoautomatico` ADD `proautclasephp` VARCHAR(50) NULL AFTER `proauttipo`;
ALTER TABLE `procesoautomatico` ADD `proautmetodo` VARCHAR(50) NULL AFTER `proautfechaejecucion`;


UPDATE `funcionalidad` SET `funcruta` = 'admin/antencion/usuario/solicitud' WHERE `funcionalidad`.`funcid` = 52; UPDATE `funcionalidad` SET `moduid` = '12' WHERE `funcionalidad`.`funcid` = 53;


ALTER TABLE `ruta` ADD `rutavalorestampilla` DECIMAL(6,0) NOT NULL DEFAULT '0' COMMENT 'Valor de la estampilla para la ruta' AFTER `muniiddestino`;
ALTER TABLE `tarifatiquete` ADD `tartiqvalorseguro` DECIMAL(6,0) NOT NULL DEFAULT '0' COMMENT 'Valor del seguro para el tiquete' AFTER `tartiqvalor`;
ALTER TABLE `tiquete` ADD `tiquvalorseguro` DECIMAL(6,0) NULL COMMENT 'Valor del seguro para el tiquete' AFTER `tiquvalorestampilla`;


(29, 'solicitudTokeFirmaContratoAsociado', 'Solicitud de token de verificación para el firmado del contrato numeroContrato', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, para avanzar con el proceso de firma electr&oacute;nica del contrato n&uacute;mero <strong>numeroContrato</strong>, es necesario que ingrese el siguiente c&oacute;digo de verificaci&oacute;n:</p>\r\n<p style=\"text-align: justify;\"><em>C&oacute;digo de Verificaci&oacute;n: <strong>tokenAcceso</strong></em></p>\r\n<p style=\"text-align: justify;\"><br>Tenga en cuenta que este token de verificaci&oacute;n ser&aacute; v&aacute;lido durante los pr&oacute;ximos <strong>tiempoToken </strong>minutos. Si transcurre este tiempo sin completar el proceso, deber&aacute; solicitar un nuevo token.</p>\r\n<p style=\"text-align: justify;\">Por favor, acceda a nuestra plataforma y proporcione el token que le hemos proporcionado. Luego, haga clic en el bot&oacute;n de firma para completar el proceso.</p>\r\n<p style=\"text-align: justify;\">Gracias por su colaboraci&oacute;n y compromiso con la seguridad de nuestros servicios.</p>\r\n<p style=\"text-align: justify;\">&nbsp;<br>Atentamente,&nbsp;</p>\r\n<p style=\"text-align: justify;\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-16 16:54:19', '2024-02-16 16:54:19');

INSERT INTO `informacionnotificacioncorreo` (`innocoid`, `innoconombre`, `innocoasunto`, `innococontenido`, `innocoenviarpiepagina`, `innocoenviarcopia`, `created_at`, `updated_at`) VALUES
INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
 (54, 7, 'Firmar contrato', 'Firmar contrato de vehículo', ' admin/direccion/transporte/firmarContratos', 'post_add_icon', 7, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

 INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(54, 1, 54);


INSERT INTO `cuentacontable` (`cueconid`, `cueconcodigo`, `cueconnombre`, `cueconnaturaleza`, `cueconactiva`, `created_at`, `updated_at`) VALUES ('12', '120012', 'CXC PAGO MENSUALIDAD PARCIAL', 'C', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO `procesoautomatico` (`proautid`, `proautnombre`, `proautfechaejecucion`, `proautmetodo`, `proauttipo`, `proautclasephp`, `created_at`, `updated_at`) VALUES ('13', 'ProcesarPagoMensualidad', '2024-02-20', 'procesarPagoMensualidad', 'N', 'Noche', '2024-02-21 14:36:48', '2024-02-21 14:36:48');
INSERT INTO `procesoautomatico` (`proautid`, `proautnombre`, `proautfechaejecucion`, `proautmetodo`, `proauttipo`, `proautclasephp`, `created_at`, `updated_at`) VALUES ('14', 'CerrarMovimientoCaja', '2024-02-20', 'cerrarMovimientoCaja', 'N', 'Noche', '2024-02-21 14:36:48', '2024-02-21 14:36:48');

INSERT INTO `informacionnotificacioncorreo` (`innocoid`, `innoconombre`, `innocoasunto`, `innococontenido`, `innocoenviarpiepagina`, `innocoenviarcopia`, `created_at`, `updated_at`) VALUES
(30, 'solicitudTokeFirmaContratoGerente', 'Solicitud de token de verificación para el firmado del contrato numeroContrato', '<p style=\"text-align: justify;\">Estimado gerente <strong>nombreGerente </strong>de la&nbsp;<em><strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></em>, queremos informarle que el contrato n&uacute;mero <strong>numeroContrato</strong>, correspondiente al asociado <strong>nombreAsociado</strong>, est&aacute; listo para ser firmado electr&oacute;nicamente. Para continuar con este proceso, necesitamos que ingrese el siguiente c&oacute;digo de verificaci&oacute;n:<br><br>C&oacute;digo de verificaci&oacute;n: <strong><em>tokenAcceso</em></strong></p>\r\n<p style=\"text-align: justify;\"><br>Este c&oacute;digo de verificaci&oacute;n estar&aacute; activo durante los pr&oacute;ximos <strong>tiempoToken </strong>minutos. Si transcurre este tiempo sin completar el proceso, deber&aacute; solicitar un nuevo token.</p>\r\n<p style=\"text-align: justify;\">Le pedimos que ingrese a nuestra plataforma y utilice el c&oacute;digo de verificaci&oacute;n proporcionado. Una vez ingresado, haga clic en el bot&oacute;n de firma para finalizar el proceso.<br>Agradecemos su colaboraci&oacute;n y compromiso con la seguridad de nuestros servicios.</p>\r\n<p style=\"text-align: justify;\"><br>Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><em><strong>Sistema de ERP de HACARITAMA</strong></em><br><em><strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></em></p>', 1, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(31, 'notificarPagoMensualidadCompletada', 'Confirmación de pago mensual', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, quiero aprovechar la oportunidad para enviarle un cordial saludo en nombre de la&nbsp;<strong><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></strong>.<br><br>Nos complace informarle que hemos registrado el pago de su compromiso mensual, el cual incluye todos los abonos que ten&iacute;a pendientes en nuestro sistema. Su puntualidad en los pagos es fundamental para el crecimiento continuo de nuestra cooperativa, y por ello queremos expresarle nuestro sincero agradecimiento.<br><br>Quedamos a su disposici&oacute;n para cualquier consulta o solicitud adicional que pueda tener.<br><br>&iexcl;Gracias nuevamente por su colaboraci&oacute;n y compromiso!<br><br></p>\r\n<p>Atentamente,</p>\r\n<p><strong><em>nombreGerente</em></strong><br><strong><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></strong></p>', 1, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(32, 'notificacionCierreCajaAutomatico', 'Recordatorio de cierre de caja pendiente', '<p style=\"text-align: justify;\">Estimado&nbsp;<strong>nombreEmpleado</strong>, como empleado de la <strong><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></strong>, es fundamental cumplir con el deber diario de cerrar todos los movimientos de caja y generar el correspondiente comprobante contable.<br><br>Lamentablemente, hemos identificado que hoy no has realizado el respectivo cierre de caja, por lo que el sistema ha procedido a cerrarla autom&aacute;ticamente. Te recordamos la importancia de cumplir con esta tarea para evitar posibles sanciones y mantener la integridad de nuestros procesos contables.<br><br>Quedamos atentos a que realices el cierre de caja pendiente y agradecemos tu compromiso con tus responsabilidades laborales.<br><br>Sin m&aacute;s por el momento, te deseamos un feliz descanso.</p>\r\n<p style=\"text-align: justify;\"><br>Atentamente,</p>\r\n<p style=\"text-align: justify;\"><br><em><strong>nombreGerente</strong></em><br><em><strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></em></p>', 1, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


ALTER TABLE `colocacionliquidacion` CHANGE `colliqnumerocomprobante` `comconid` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Número de comprobante de pago de la cuota de la colocación';


INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(55, 13, 'PDF', 'Generar informes en PDF', 'admin/informes/pdf', 'picture_as_pdf_icon', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(56, 13, 'Descargable', 'Generar informes en descargable', 'admin/informes/descargable', 'cloud_download_icon', 2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(55, 1, 55),
(56, 1, 56);



ALTER TABLE `vehiculoresponsabilidad` ADD `created_at` TIMESTAMP NULL AFTER `vehresvalorpagado`, ADD `updated_at` TIMESTAMP NULL AFTER `created_at`;

ALTER TABLE `vehiculotarjetaoperacion` CHANGE `vetaopaid` `vetaopid` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo tarjeta operación';

ALTER TABLE `colocacionliquidacion` CHANGE `comconid` `comconid` BIGINT(20) UNSIGNED NULL COMMENT 'Identificador del comprobante contable';
ALTER TABLE `colocacionliquidacion` CHANGE `colliqvalordescuentoanticipado` `colliqvalorinteresdevuelto` DECIMAL(10,0) NULL DEFAULT NULL COMMENT 'Valor interés devuelto en el pagado a la colocación';


ALTER TABLE `ruta`  DROP `rutavalorestampilla`;
ALTER TABLE `tarifatiquete` ADD `tartiqvalorestampilla` DECIMAL(6,0) NULL COMMENT 'Valor de la estampilla para el tiquete' AFTER `tartiqfondoreposicion`;
UPDATE `tarifatiquete` SET `tartiqvalorestampilla` = '0' WHERE `tarifatiquete`.`tartiqid` = 1; UPDATE `tarifatiquete` SET `tartiqvalorestampilla` = '0' WHERE `tarifatiquete`.`tartiqid` = 2;



ALTER TABLE `cuentacontable` ADD `cuecontitulo` VARCHAR(50) NOT NULL AFTER `cueconid`;


ALTER TABLE `ruta` CHANGE `depaidorigen` `rutadepaidorigen` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de origen de la ruta', CHANGE `muniidorigen` `rutamuniidorigen` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de origen de la ruta', CHANGE `depaiddestino` `rutadepaiddestino` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de destino de la ruta', CHANGE `muniiddestino` `rutamuniiddestino` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de destino de la ruta';

ALTER TABLE `tarifatiquete` CHANGE `depaidorigen` `tartiqdepaidorigen` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de origen del tiquete', CHANGE `muniidorigen` `tartiqmuniidorigen` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de origen del tiquete';

ALTER TABLE `encomienda` CHANGE `depaidorigen` `encodepaidorigen` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de origen de la encomienda', CHANGE `muniidorigen` `encomuniidorigen` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de origen de la encomienda', CHANGE `depaiddestino` `encodepaiddestino` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de destino de la encomienda', CHANGE `muniiddestino` `encomuniiddestino` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de destino de la encomienda';

ALTER TABLE `tiquete` CHANGE `depaidorigen` `tiqudepaidorigen` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de origen del tiquete', CHANGE `muniidorigen` `tiqumuniidorigen` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de origen del tiquete', CHANGE `depaiddestino` `tiqudepaiddestino` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de destino del tiquete', CHANGE `muniiddestino` `tiqumuniiddestino` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de destino del tiquete';

ALTER TABLE `tarifatiquete` ADD `tartiqdepaiddestino` TINYINT(3) NULL AFTER `tartiqmuniidorigen`, ADD `tartiqmuniiddestino` SMALLINT(5) NULL AFTER `tartiqdepaiddestino`;


ALTER TABLE `rutanodo` ADD `rutnoddepaid` TINYINT(3) NULL COMMENT 'Identificador del departamento del nodo de la ruta' AFTER `rutaid`;
ALTER TABLE `rutanodo` CHANGE `muniid` `rutnodmuniid` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio del nodo de la ruta';

ALTER TABLE `tarifatiquete` ADD `tartiqvalorfondorecaudo` DECIMAL(6,0) NULL AFTER `tartiqfondoreposicion`;