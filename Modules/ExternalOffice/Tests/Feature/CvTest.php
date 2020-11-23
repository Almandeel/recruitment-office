<?php

namespace Modules\ExternalOffice\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExternalOffice\Models\Cv;

class CvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_cv()
    {
        // $this->be();
        $data = factory(Cv::class)->raw();
        $response  = $this->post(route('cvs.store'), $data)->assertRedirect(route('cvs.index'));
    }
}
