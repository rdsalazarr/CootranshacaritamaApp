ALTER TABLE vehiculosuspendido CHANGE vehsusfechafinalsuspencion vehsusfechafinalsuspencion DATE NULL COMMENT 'Fecha inicial de la suspención del vehículo';

INSERT INTO procesoautomatico (proautid, proautnombre, proautclasephp, proautmetodo, proautfechaejecucion, proauttipo, created_at, updated_at) 
VALUES (NULL, 'SuspenderVehiculosProgramado', 'suspenderVehiculosProgramados', 'Dia', '2024-06-14', 'D', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);