<?php
/**
 * Copyright (c) 2017.
 * MSmvc
 *
 * An open source application development framework for PHP
 *
 * Copyright (c) 2017 Maurice Schurink, All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains the property of  Maurice Schurink. The intellectual and
 * technical concepts contained herein are proprietary to Maurice Schurink and may be covered by U.S. and Foreign
 * Patents, patents in process, and are protected by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior written permission is obtained from  Maurice
 * Schurink.  Access to the source code contained herein is hereby forbidden to anyone except current  Maurice Schurink
 * employees, managers or contractors who have executed Confidentiality and Non-disclosure agreements explicitly
 * covering such access.
 *
 * The copyright notice above does not evidence any actual or intended publication or disclosure  of  this source code,
 * which includes information that is confidential and/or proprietary, and is a trade secret, of  Maurice Schurink. ANY
 * REPRODUCTION, MODIFICATION, DISTRIBUTION, PUBLIC  PERFORMANCE, OR PUBLIC DISPLAY OF OR THROUGH USE  OF THIS  SOURCE
 * CODE  WITHOUT  THE EXPRESS WRITTEN CONSENT OF Maurice Schurink IS STRICTLY PROHIBITED, AND IN VIOLATION OF
 * APPLICABLE LAWS AND INTERNATIONAL TREATIES.  THE RECEIPT OR POSSESSION OF  THIS SOURCE CODE AND/OR RELATED
 * INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS TO REPRODUCE, DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO
 * MANUFACTURE, USE, OR SELL ANYTHING THAT IT  MAY DESCRIBE, IN WHOLE OR IN PART.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Except as contained in this notice, the name of MSmvc. shall not be used in advertising or otherwise to promote the
 * sale, use or other dealings in this Software without prior written authorization from Maurice Schurink.
 *
 */

namespace App\system;

use App\system\fields\Model;
use App\system\pipelines\MS_pipeline;

/**
 * Class TemplateHandler
 * @package App\system
 */
class TemplateHandler {
    /**
     * name of the template file
     * @var string
     */
    private $template;

    /**
     * file content
     * @var string
     */
    private $content;
    /**
     * @var array
     */
    private $replacements;

    /**
     * TemplateHandler constructor.
     *
     * @param string $template
     */
    function __construct(string $template = "migration-base.php.mst") {
        $this->setTemplate($template);
    }

    /**
     * @param null $name
     * @param null $location
     */
    public function createFile($name = NULL, $location = NULL) {
        $this->readTemplate($name);
        $this->applyReplacements();
        file_put_contents(MS_pipeline::$root . "$location/$name." . end($this->template["extensions"]), $this->content);
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template) {
        $this->template = $this->fileInfo($template);
    }

    /**
     * this function will apply the replacements to the content
     */
    private function applyReplacements() {
        foreach ($this->replacements as $target => $replacement) {
            if (is_array($replacement)) {
                $replacementString = "";
                foreach ($replacement as $item) {
                    $replacement .= $item . PHP_EOL;
                }
                str_replace($target, $replacementString, $this->content);
            }
            str_replace($target, $replacement, $this->content);
        }
    }

    /**
     * @param mixed $replacements
     */
    public function setReplacements(array $replacements) {
        $this->replacements = $replacements;
    }

    /**
     * @param array $file
     *
     * @return array
     */
    private function getFileExtensions(array $file) {
        //todo: build pathinfo of original and add trimmed name
        $extensions = [];
        while (isset($file["extension"])) {
            $extensions[] = $file["extension"];
            $file = pathInfo($file["filename"]);
        }
        return ["trimmedName" => $file["filename"], "extensions" => $extensions];
    }

    /**
     * @param string $file : filepath
     *
     * @return array
     */
    private function fileInfo(string $file) {
        return array_merge(pathinfo($file), $this->getFileExtensions(pathinfo($file)));
    }

    /**
     * @return mixed
     */
    public function getTemplate() {
        return "App/system/templates/" . $this->template["basename"];
    }

    private function readTemplate($name) {
        $file = new MS_pipeline($this->getTemplate());
        $this->content = str_replace("templateClass", $name, $file->getDataSetFromRequest());
    }

    /**
     * @param Model $model
     */
    private function fillMigration(Model $model) {
    }
}