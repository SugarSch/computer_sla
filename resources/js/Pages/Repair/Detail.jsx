import React from "react";
import { router } from '@inertiajs/react';

function RepairDetail(props){

    let {repair, attachments, can_see_status, user_id, repair_action_type, slaStatus} = props;
    function deleteFile(id) {
        if (confirm('คุณต้องการลบไฟล์นี้หรือไม่?')) {
            router.delete(`/attachment-remove/${id}`)
        }
    }
    return (
        <div className="container pt-5">
            <h3 className="text-center mb-4">{repair.title}</h3>
            <div className="card shadow p-4">
                {can_see_status && (<div>
                    <label className="form-label">สถานะ:</label> {repair.repair_status.label}
                </div>)}
                {repair.repair_status.code !== 'new' && repair.repair_status.code !== 'completed' && repair.repair_status.code !== 'cancelled'
                     && (
                    <div className={`alert alert-${slaStatus.color} text-center`}>
                        <strong>SLA Status: </strong> {slaStatus.text}
                    </div>
                )}
                <div>
                    <label className="form-label">ผู้แจ้ง:</label> {repair.user.username}
                </div>
                <div>
                    <label className="form-label">ช่างผู้รับผิดชอบ:</label> {repair.assigned_to_user?.username ?? "-"}

                </div>
                <label className="form-label">รายละเอียด:</label>
                <div>
                    {repair.description}
                </div>
            </div>
            <div className="text-right pt-3">
                {
                    repair_action_type.map(function (action) {
                        if(action.code != 'create_request' || (action.code == 'cancel_request' && repair.assigned_to_user)){
                            return (<span className="ps-3">
                                <a href={"/repair/"+repair.id+"/"+action.code} 
                                    className={`btn btn-sm btn-${action.code === 'cancel_request' ? 'danger' : 'primary'}`}>
                                        {action.label}
                                </a>
                                </span>)
                        }
                    })
                }
            </div>
            <label className="form-label pt-3">ไฟล์แนบ</label>
            <ul className="list-group">
                {attachments.lenght > 0 ? attachments.map(file => (
                    <li key={file.id} className="list-group-item d-flex justify-content-between align-items-center">
                        <a href={file.file_path}
                            target="_blank" rel="noopener noreferrer">
                            {file.file_name}
                        </a>
                        { (file.uploaded_by == user_id) &&
                            (<button onClick={() => deleteFile(file.id)} className="btn btn-sm btn-danger">
                                ลบ
                            </button>)
                        }
                    </li>
                )) : <li>ไม่มีไฟล์แนบ</li>}
            </ul>
        </div>);
}

export default RepairDetail;