 🔠 Clases del modelo (lado objetos – PHP)
 
📦 Empresa
•	idEmpresa (PK)
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
•	idViaje (PK)
•	origen
•	destino
•	vcantMaxPasajeros
•	idEmpresa (FK → Empresa)
•	rNumeroEmpleado (FK → ResponsableV)
•	vImporte
•	Relación: cada viaje tiene un responsable

👤 ResponsableV
•	rnumeroEmpleado (PK)
•	rnumeroLicencia
• rdocumento
• activo (para borrado logico)
•	Relación: 

🧍 Pasajero
•	pDocumento (PK)
•	idViaje
•	activo (para borrado logico)
•	Relación: 

*Se penso una tabla intermedia entre Pasajero → Viaje, pero no se implemento
🔗 ViajePasajero (clase que representa la tabla intermedia)
•	idPasajero (PK, FK)
•	codigoViaje (PK, FK)
•	(Opcional: asiento, fechaReserva, etc.)
