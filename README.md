 🔠 Clases del modelo (lado objetos – PHP)
 
📦 Empresa
•	idEmpresa 
•	nombre
•	direccion
•	Relación: tiene muchos Viajes.

👤 Persona
• documento
• nombre
• apellido
• telefono
• activo (para borrado logico)

🚍 Viaje
•	idViaje 
•	origen
•	destino
•	vcantMaxPasajeros
•	idEmpresa 
•	rNumeroEmpleado
•	vImporte
•	Relación: cada viaje tiene un responsable

👤 ResponsableV
•	rnumeroEmpleado 
•	rnumeroLicencia
• rdocumento
• activo (para borrado logico)
•	Relación: 

🧍 Pasajero
•	pDocumento 
•	idViaje
•	activo (para borrado logico)
•	Relación: 

*Se penso una tabla intermedia entre Pasajero → Viaje, pero no se implemento
🔗 ViajePasajero (clase que representa la tabla intermedia)
•	idPasajero (PK, FK)
•	codigoViaje (PK, FK)
•	(Opcional: asiento, fechaReserva, etc.)
