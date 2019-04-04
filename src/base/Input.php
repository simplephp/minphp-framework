<?php

/**
 * description     Input 组件
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */

namespace min\base;

class Input extends Component
{
    /**
     * @var array shared component instances indexed by their IDs
     */
    private $_command = [];
    /**
     * @var array component definitions indexed by their IDs
     */
    private $_scriptFileName = null;

    private $_options = [];

    /**
     *   入口文件：mix-httpd
     *   命令：service start
     *   选项：-d
     */
    public function _onInitialize() {

        $options = [];
        $params = $GLOBALS['argv'];

        $this->_scriptFileName = isset($params[0]) ? $params[0] : '';

        foreach ($params as $key => $value) {
            // 获取命令
            if (in_array($key, [1, 2])) {
                $this->_command[] = $value;
            }
            // 获取选项
            if ($key > 2) {
                if (substr($value, 0, 2) == '--') {
                    $options[] = substr($value, 2);
                } else if (substr($value, 0, 1) == '-') {
                    $options[] = substr($value, 1);
                }
            }
        }
        parse_str(implode('&', $options), $options);
        // 设置选项默认值
        foreach ($options as $name => $value) {
            if ($value === '') {
                $options[$name] = true;
            }
        }

        $this->_options = $options;
    }

    /**
     * 获取 command
     * @return string
     */
    public function getCommand()
    {
        return implode(' ', $this->_command);
    }

    /**
     * 获取 controller name
     * @return mixed|string
     */
    public function getControllerName()
    {
        return isset($this->_command[0]) ? $this->_command[0] : '';
    }


    /**
     * 获取 action name
     * @return mixed|string
     */
    public function getActionName()
    {
        return isset($this->_command[1]) ? $this->_command[1] : '';
    }

    /**
     * 获取 options
     * @return array
     */
    public function getOptions() {
        return $this->_options;
    }

    /**
     * 获取脚本名称
     * @return array
     */
    public function getScriptFileName() {
        return $this->_scriptFileName;
    }
}
