<?php

/**
 * This file is part of Laravel Core by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Core\Providers\Common;

use use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This is the paginate provider trait.
 *
 * @package    Laravel-Core
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Core/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Core
 */
trait PaginateProviderTrait
{
    /**
     * The paginated links generated by the paginate method.
     *
     * @var string
     */
    protected $paginateLinks;

    /**
     * Get a paginated list of the models.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate()
    {
        $model = $this->model;

        if (property_exists($model, 'order')) {
            $values = $model::orderBy($model::$order, $model::$sort)->paginate($model::$paginate, $model::$index);
        } else {
            $values = $model::paginate($model::$paginate, $model::$index);
        }

        if ($values->getCurrentPage() > $values->getLastPage()) {
            throw new NotFoundHttpException();
        }

        if (!$values->getTotal()) {
            $this->paginateLinks = $values->links();
        }

        return $values;
    }

    /**
     * Get the paginated links.
     *
     * @return string
     */
    public function links()
    {
        return $this->paginateLinks;
    }
}
