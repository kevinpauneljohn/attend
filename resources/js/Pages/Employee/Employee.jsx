import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import {Head, usePage} from "@inertiajs/react";
import Modal from "@/Components/Modal.jsx";
import React, {useState} from "react";
import ConnectEmployeeForm from "@/CustomComponents/ConnectEmployeeForm.jsx";

export default function Employee({ auth,  owner_id, biometric_users}) {


    const [editModal, setEditModal] = useState(false);
    const [employeeId, setEmployeeId] = useState(null);
    const [employeeName, setEmployeeName] = useState("");
    const [selectedUserId, setSelectedUserId] = useState("");
    const { employees, used_biometrics_userid } = usePage().props;
    const openEditModal = (element) => {
        let employeeName = document.getElementById('employee-name-'+element.currentTarget.id).innerText;
        let selectedUserId = document.getElementById('biometrics-id-'+element.currentTarget.id).innerText;

        setEmployeeName(employeeName);
        setSelectedUserId(selectedUserId);
        setEditModal(true);
        setEmployeeId(element.currentTarget.id);
    }

    const closeEditModal = (editModal) => {
        setEditModal(editModal);
        setEmployeeId(null);
    };


    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>}
        >
            <Head title="Employees"/>
            <div className="py-12">
                <div className="mx-12">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                        <table className="table-auto border-2 border-solid w-full my-20 mx-20" id="employee-table-1">
                            <thead>
                            <tr>
                                <th className="px-4 py-4 text-left border-2 border-solid"></th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Name</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Email</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Username</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Mobile Number</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Date of birth</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Spa</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Date Added</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Biometrics Id</th>
                                <th className="px-4 py-4 text-left border-2 border-solid">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                {
                                    employees.map((item, index) => {
                                        return (
                                            <tr key={index}>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.user.id}</td>
                                                <td className="px-2 py-1 text-left border-2 border-solid"
                                                    id={"employee-name-" + item.id}>{item.full_name}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.user.email}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.user.username}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.user.mobile_number}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.user.date_of_birth}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.spa_name}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">{item.date_added}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid" id={"biometrics-id-"+item.id}>{item.biometrics_id}</td>
                                                <td className="px-4 py-3 text-left border-2 border-solid">
                                                    <button
                                                        className="bg-green-700 hover:bg-green-600 transition text-white font-bold py-1 px-2 rounded m-1">
                                                        View
                                                    </button>
                                                    <button
                                                        className="bg-blue-500 hover:bg-blue-400 transition text-white font-bold py-1 px-2 rounded m-1"
                                                        onClick={openEditModal}
                                                        id={item.id}
                                                    >
                                                        Connect
                                                    </button>
                                                    <button
                                                        className="bg-purple-900 hover:bg-purple-800 transition text-white font-bold py-1 px-2 rounded m-1">
                                                        Edit
                                                    </button>
                                                    <button
                                                        className="bg-red-600 hover:bg-red-500 transition text-white font-bold py-1 px-2 rounded m-1">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        );
                                    })
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <Modal show={editModal} onClose={closeEditModal}>
                <ConnectEmployeeForm
                    biometric_users={biometric_users}
                    owner_id={owner_id}
                    employeeName={employeeName}
                    close_modal={closeEditModal}
                    employee_id = {employeeId}
                    used_biometrics_userid = {used_biometrics_userid}
                    selectedUserId={selectedUserId}
                />
            </Modal>

        </AuthenticatedLayout>
    );
}
