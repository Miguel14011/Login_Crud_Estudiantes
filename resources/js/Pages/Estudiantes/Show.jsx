import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

export default function Show({ estudiante }) {
    const { isAdmin } = usePage().props.auth;
    const filas = [
        ['Nombre', estudiante.nombre],
        ['Apellido', estudiante.apellido],
        ['Email', estudiante.email],
        ['Carrera', estudiante.carrera],
        ['Semestre', estudiante.semestre],
        ['Registrado', new Date(estudiante.created_at).toLocaleString('es-CO')],
    ];

    return (
        <AppLayout
            title="Detalle del estudiante"
            actions={
                <div className="flex gap-2">
                    <Link href="/estudiantes" className="rounded border border-slate-300 bg-white px-4 py-2 text-sm hover:bg-slate-50">
                        Volver
                    </Link>
                    {isAdmin && (
                        <Link
                            href={`/estudiantes/${estudiante.id}/edit`}
                            className="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            Editar
                        </Link>
                    )}
                </div>
            }
        >
            <Head title={`${estudiante.nombre} ${estudiante.apellido}`} />
            <dl className="divide-y divide-slate-100 rounded-lg bg-white shadow">
                {filas.map(([k, v]) => (
                    <div key={k} className="grid grid-cols-3 px-6 py-3">
                        <dt className="text-sm font-medium text-slate-500">{k}</dt>
                        <dd className="col-span-2">{v}</dd>
                    </div>
                ))}
            </dl>
        </AppLayout>
    );
}
