Sistema Web Administrativo de Control Bibliotecario

Sistema web monousuario desarrollado para automatizar y optimizar la gestión de inventario, circulación de textos y control de préstamos bibliotecarios. Diseñado originalmente como proyecto de desarrollo para la U.E. Colegio Fundación "Carlos Delfino", este repositorio contiene el código fuente de libre consulta y adaptación.



Stack Tecnológico

- **Backend:** PHP 8.4 / Laravel 12 (Arquitectura MVC, Eloquent ORM, Form Requests).
- **Base de Datos:** PostgreSQL 16 (Transacciones ACID, restricciones de integridad referencial `ON DELETE CASCADE` y consultas avanzadas `ILIKE`).
- **Frontend:** Blade Templates, Bootstrap 5, Tailwind CSS, DataTables, Select2 & SweetAlert2.
- **Generación de Documentos:** DomPDF (`barryvdh/laravel-dompdf` para comprobantes y reportes generales).
- **Entorno de Desarrollo Virtualizado:** Debian 13 (Bookworm) sobre Oracle VirtualBox 7.0.



Módulos y Arquitectura del Sistema

1-Módulo de Inventario de Libros
- **Catalogación Estructurada:** Registro de obras con control de ISBN (10/13 dígitos), clasificación por sistema Dewey, estado físico en observaciones y stock_total.
- **Búsqueda Avanzada Case-Insensitive:** Consultas optimizadas con operadores `ILIKE` en PostgreSQL sin distinción de mayúsculas o minúsculas.
- **Reglas de Validación Estragadas:** Control mediante expresiones regulares (`regex`) para evitar nombres con caracteres numéricos e impedir el registro de datos vacíos o duplicados.

2-Módulo de Gestión de Estudiantes
- **Expediente de Alumnos:** Registro con cédula de identidad única, grado, sección y asignación de docente guía.
- **Historial Transaccional:** Trazabilidad individual de cada estudiante para verificar libros solicitados y préstamos activos.

3-Circulación: Préstamos, Devoluciones y Prórrogas
- **Control Transaccional de Stock:** Manejo de salidas con decremento e incremento automático del inventario físico mediante transacciones atómicas.
- **Lógica Anti-Sobrepréstamo:** Verificación en tiempo real del stock disponible (`stock_total > 0`) antes de procesar la solicitud.
- **Prórroga / Extensión Automática:** Botón para añadir lapsos de renovación (+7 días) al plazo de entrega del préstamo.

4-Dashboard, Reportes e Indicadores
- **Indicadores Clave:** Métricas instantáneas de libros disponibles, préstamos activos y devoluciones vencidas calculadas dinámicamente con `Carbon`.
- **Ranking Histórico y Mensual:** Estadísticas automáticas para identificar los libros más populares del mes y de toda la historia.
- **Generación de Comprobantes PDF:** Emisión automática de tickets de préstamo imprimibles y reportes generales de inventario ordenados por Dewey.



Modelo de Base de Datos (3FN)

El diseño relacional cumple estrictamente con la **Tercera Forma Normal (3FN)**:
- **`libros`**: `id`, `titulo`, `autor`, `isbn`, `categoria`, `stock_total`, `observaciones`, `timestamps`.
- **`estudiantes`**: `id`, `nombre_completo`, `cedula`, `grado`, `docente_guia`, `timestamps`.
- **`prestamos`**: `id`, `libro_id` (FK), `estudiante_id` (FK), `fecha_prestamo`, `fecha_devolucion`, `estado`, `timestamps`.



Credenciales de Acceso Demo

Para explorar el panel administrativo en un entorno local:

- **Email:** `admin@admin.com`
- **Contraseña:** `admin123`
