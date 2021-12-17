<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ParseTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            'scope' => [
                'indirect-emissions-owned',
                'electricity',
            ],
            ['name' => 'meeting-rooms'],
        ];

        $classTemplate = file_get_contents( base_path( 'classTemplates/template.php' ) );

        $tableName = $data[0]['name'];
        $name      = str_replace( '-', '', ucwords( $data[0]['name'], '-' ) );

        #Create models path.

        $modelsPath = 'Models/';

        $modelsPath = $this->createModelsPath( $modelsPath, $data );

        #Create namespace

        $namespace = 'App/' . substr( $modelsPath, 0, - 1 );
        $namespace = str_replace( '/', '\\', $namespace );

        #Replace original template

        $replacedTemplate = $this->replaceTemplate( $name, $tableName, $namespace, $classTemplate );

        #Store the file local or remotely

        $store = Storage::disk( 'base' )->put( $modelsPath . $name . '.php', $replacedTemplate );

        self::assertTrue( $store );
    }

    private function replaceTemplate( $name, $tableName, $namespace, $classTemplate ) {
        $classTemplate = str_replace( '{class_name}', $name, $classTemplate );
        $classTemplate = str_replace( '{table_name}', "'" . $tableName . "'", $classTemplate );
        $classTemplate = str_replace( '{namespace}', $namespace, $classTemplate );

        return $classTemplate;
    }

    private function createModelsPath( $modelsPath, $data ) {
        foreach ( $data['scope'] as $scope ) {
            $scope      = str_replace( '-', '', ucwords( $scope, '-' ) );
            $modelsPath .= $scope . '/';
        }

        return $modelsPath;
    }

}
