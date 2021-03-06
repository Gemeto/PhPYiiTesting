<?php

class IngestController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view', 'published'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'shared2me', 'index'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        $comment=new Comment;

        if(isset($_POST['Sharedingest'])) {
            foreach ($_POST['Sharedingest']['user_id'] as $uid) {
                $shareingest = new Sharedingest();
                $shareingest->ingest_id = $id;
                $shareingest->user_id = $uid;
                $shareingest->save();
            }
        }

        if(isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            $comment->ingest_id = $id;
            $comment->save();
        }

        $ingests = Ingest::model()->findAll("id='{$id}'");
        if (isset($ingests[0])) {
            $food = Food::model()->findAll("id='{$ingests[0]->food_id}'");
            $foodN = isset($food[0]) ? $food[0]->Nombre : "No se ha podido encontrar la comida.";
            $shareingest = new Sharedingest;
            $this->render('view', array(
                'model' => $this->loadModel($id),
                'comment' => $comment,
                'singest' => $shareingest,
                'foodN' => $foodN,
            ));
        }
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ingest;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ingest'])) {
            $model->attributes = $_POST['Ingest'];
            if ($model->save()) {
                if(isset($_POST['Sharedingest'])) {//Comprobamos si el usuario ha compartido la comida con alguien
                    foreach ($_POST['Sharedingest']['user_id'] as $uid) {
                        $shareingest = new Sharedingest();
                        $shareingest->ingest_id = $model->id;
                        $shareingest->user_id = $uid;
                        $shareingest->save();
                    }
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ingest']))
		{
			$model->attributes=$_POST['Ingest'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	    $usr_id = Yii::app()->user->id;
	    $criteria = new CDbCriteria;
        $criteria->condition = "user_id = '{$usr_id}'";
		$dataProvider=new CArrayDataProvider(Ingest::model()->findAll($criteria));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

    public function actionShared2Me()
    {
        $usr_id = Yii::app()->user->id;
        $criteria = new CDbCriteria;
        $criteria->condition = "id IN(SELECT ingest_id FROM sharedingest WHERE user_id = {$usr_id})";
        $dataProvider=new CArrayDataProvider(Ingest::model()->findAll($criteria));
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    public function actionPublished()
    {
        $dataProvider=new CArrayDataProvider(Ingest::model()->findAll("public = 1"));
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ingest('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ingest']))
			$model->attributes=$_GET['Ingest'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ingest the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ingest::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ingest $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ingest-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}