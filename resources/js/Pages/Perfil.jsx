import { Head } from '@inertiajs/react';
import AppLayout from '../Layouts/AppLayout';
import FichaEstudiante from '../Components/FichaEstudiante';

export default function Perfil({ estudiante }) {
    return (
        <AppLayout title="Mi perfil">
            <Head title="Mi perfil" />
            <FichaEstudiante estudiante={estudiante} />
            <p className="mt-4 text-sm text-slate-500">
                Si necesitas corregir algún dato, comunícate con el administrador.
            </p>
        </AppLayout>
    );
}
