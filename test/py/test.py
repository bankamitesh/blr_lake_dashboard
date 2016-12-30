
import scipy.interpolate 


def main():
    x = [1, 2.5, 3.4,  5.8, 6] 
    y = [2, 4,   5.8,  4.3, 4]
    y_func = scipy.interpolate.interp1d(x, y, kind='cubic')

    print y_func(1.0)
    print y_func(2.0)
    print y_func(3.0)
    print y_func(4.0)
    print y_func(5.0)
    print y_func(6.0)


if __name__ == "__main__" :
    main() 



