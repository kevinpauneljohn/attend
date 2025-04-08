import TextInput from "@/Components/TextInput.jsx";
import InputLabel from "@/Components/InputLabel.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import {router, useForm} from "@inertiajs/react";

export default function ConnectEmployeeForm({biometric_users, owner_id, employeeName, close_modal, employee_id, used_biometrics_userid, selectedUserId}) {

    const {data, setData, post, processing, errors} = useForm({
        biometrics_user: ''
    });

     const connectEmployeeToBiometrics = async (event) => {
        event.preventDefault();

         try{
             const response = await axios.post(`/connect-employee/${employee_id}`,{
                 biometrics_user: data.biometrics_user
             },
                 {
                     headers: {
                         "Content-Type": "application/json"
                     }
                 });

             alert(response.data.message);
             if(response.data.success === true)
             {
                 close_modal(false);
                 router.reload({only: ["employees","used_biometrics_userid"]});
             }

             console.log(response)
         }
         catch (error){
             console.error('Error submitting form:', error.response?.data || error.message);
         }
    }

    const checkUserIfIfTaken = (user) => {
       const userIDs = Object.values(used_biometrics_userid);
       return userIDs.includes(user);
    }

    const selectedUser = () => {
         if(selectedUserId === '')
         {
             return data.biometrics_user;
         }
         return selectedUserId;
    }

    const closeModal = () => {
        close_modal(false);
    }

    return (
        <form className="p-6" onSubmit={connectEmployeeToBiometrics}>
            <TextInput
                type="hidden"
                name="owner_id"
                value={owner_id}
            />
            <h2 className="text-lg font-medium text-gray-900">
                Connect <span className="font-bold text-red-700">{employeeName}</span> to biometric user?
            </h2>

            <p className="mt-1 text-sm text-gray-600">
                Connect biometrics user id to employee
            </p>

            <div className="mt-6">
                <InputLabel htmlFor="password" value="Password" className="sr-only"/>

                <select
                    name="biometrics_user"
                    className="w-full"
                    value={selectedUser()}
                    onChange={ e => setData('biometrics_user', e.target.value)}
                >
                        <option value=""> -- Select --</option>
                        {
                            Object.entries(biometric_users).map(([key, value]) => {
                                return <option key={key} value={value.userid} disabled={checkUserIfIfTaken(value.userid)}>{value.name} - {value.userid}</option>;
                            })
                        }
                </select>
            </div>

            <div className="mt-6 flex justify-end">
                <SecondaryButton type="button" onClick={closeModal}>Cancel</SecondaryButton>

                <PrimaryButton className="ms-3" type="submit">
                    Connect
                </PrimaryButton>
            </div>
        </form>
    );
}
