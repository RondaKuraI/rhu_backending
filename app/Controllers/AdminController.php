<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MedicineModel;
use App\Models\AuditModel;
use CodeIgniter\API\ResponseTrait;

use App\Controllers\BaseController;

class AdminController extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        //
    }

    public function getMedicines(){
        $medicine = new MedicineModel();
        $data = $medicine->findAll();
        return $this->respond($data, 200);
    }

    public function newmedicine(){
        $json = $this->request->getJSON();
        $data = [
            'ndc' => $json->ndc,
            'med_name' => $json->med_name,
            'med_type' => $json->med_type,
            'stocks' => $json->stocks,
            'expiry_date' => $json->expiry_date,
            'status' => 'active',
        ];
        $model = new MedicineModel();
        $r = $model->save($data);
        return $this->respond($r, 200);
    }

    public function updateMedicine($id){
        $data = $this->request->getJSON();
        $model = new MedicineModel();
        $model->update($id, $data);

        return $this->respond(['status' => 'success', 'message' => 'Product Updated Successfully!']);
    }

    public function updateStocks()
    {
      $ndc = $this->request->getVar('ndc');
      $stocks = $this->request->getVar('stocks');
      $medicine = new MedicineModel();
      $audit = new AuditModel();
      $pr = $medicine->where('ndc', $ndc)->first();
      if($pr){
        $nq = $pr['stocks'] + $stocks;
        $medicine->set('stocks', $nq)->where('ndc', $ndc)->update();
        $data = [
          'medicineID' =>$pr['id'],
          'oldStocks' =>$pr['stocks'],
          'stocks' => $stocks,
          'type' => 'added'
        ];
        $audit->save($data);
      }

    }
}
