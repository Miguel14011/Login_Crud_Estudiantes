// Datos de un estudiante en formato de ficha (detalle del admin y "Mi perfil")
export default function FichaEstudiante({ estudiante }) {
    const filas = [
        ['Nombre', estudiante.nombre],
        ['Apellido', estudiante.apellido],
        ['Cédula', estudiante.cedula],
        ['Usuario', estudiante.username],
        ['Email', estudiante.email],
        ['Facultad', estudiante.carrera?.facultad?.nombre],
        ['Carrera', estudiante.carrera?.nombre],
        ['Semestre', estudiante.semestre],
        ['Registrado', new Date(estudiante.created_at).toLocaleString('es-EC')],
    ];

    return (
        <dl className="divide-y divide-slate-100 rounded-lg bg-white shadow">
            {filas.map(([k, v]) => (
                <div key={k} className="grid grid-cols-3 px-6 py-3">
                    <dt className="text-sm font-medium text-slate-500">{k}</dt>
                    <dd className="col-span-2">{v ?? '—'}</dd>
                </div>
            ))}
        </dl>
    );
}
