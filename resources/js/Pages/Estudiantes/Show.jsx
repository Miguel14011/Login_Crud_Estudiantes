import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import FichaEstudiante from '../../Components/FichaEstudiante';

export default function Show({ estudiante }) {
    return (
        <AppLayout
            title="Detalle del estudiante"
            actions={
                <div className="flex gap-2">
                    <Link href="/estudiantes" className="rounded border border-slate-300 bg-white px-4 py-2 text-sm hover:bg-slate-50">
                        Volver
                    </Link>
                    <Link
                        href={`/estudiantes/${estudiante.id}/edit`}
                        className="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Editar
                    </Link>
                </div>
            }
        >
            <Head title={`${estudiante.nombre} ${estudiante.apellido}`} />
            <FichaEstudiante estudiante={estudiante} />
        </AppLayout>
    );
}
