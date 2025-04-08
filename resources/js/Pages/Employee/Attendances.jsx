import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Attendances({ auth, attendances }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Attendances</h2>}
        >
            <Head title="Attendances" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                        <table className="table-auto border-2 border-solid w-full my-20 mx-20">
                            <thead>
                            <tr>
                                <th className="px-4 py-4 text-left border-2 border-solid">Biometrics id</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">id</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Name</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Timestamp</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Type</th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                attendances.map((item, index) => {
                                    return (
                                        <tr key={index}>
                                            <td className="px-4 py-3 text-left border-2 border-solid">{item.uid}</td>
                                            <td className="px-4 py-3 text-left border-2 border-solid">{item.id}</td>
                                            <td className="px-4 py-3 text-left border-2 border-solid">{item.name}</td>
                                            <td className="px-4 py-3 text-left border-2 border-solid">{item.timestamp}</td>
                                            <td className="px-4 py-3 text-left border-2 border-solid">{item.type}</td>
                                        </tr>
                                    );
                                })
                            }

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
