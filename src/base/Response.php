<?php

/**
 * description     Input 组件
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */

namespace min\base;

class Response extends Component
{
    /**
     * @var array shared component instances indexed by their IDs
     */
    private $_command = [];
    /**
     * @var array component definitions indexed by their IDs
     */
    private $_scriptFileName = null;


    public function afterInitialize()
    {
        $this->initialize();
    }

    /**
     * 初始化
     */
    public function initialize() {

        $options = [];
        $params = $GLOBALS['argv'];
        array_shift($params);
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


    public function getCommand() {

        return $this->_command;
    }

    public function getScriptFileName() {
        return $this->_scriptFileName;
    }
}
